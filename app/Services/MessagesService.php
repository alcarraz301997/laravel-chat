<?php

namespace App\Services;

use App\Http\Resources\MessagesResource;
use App\Models\Messages;
use App\Models\Participants;
use App\Traits\HasResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessagesService
{
    use HasResponse;
    private $userJwt;

    public function __construct()
    {
        $this->userJwt = JWTAuth::user();
    }

    public function list($id, $withPagination)
    {
        $messages = Messages::where('conversation_id', $id)->messageFilters();

        $messages = !empty($withPagination)
            ? $messages->paginate($withPagination['perPage'], page: $withPagination['page'])
            : $messages->get();

        $messages = MessagesResource::collection($messages->load('conversation', 'user', 'contentType'));

        return $this->successResponse('Lectura exitosa.', $messages);
    }

    public function store($params)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateParticipant($params['conversation_id']);
            if (!$validate->original['status']) return $validate;

            $params['user_id'] = $this->userJwt->id;

            if($params['type_content_id'] != 1) {
                $name = $params['content']->getClientOriginalName();
                $extension = $params['content']->getClientOriginalExtension();
                $mime = $params['content']->getClientMimeType();

                $route = "public/chats/" . $params['conversation_id'] . $this->getMime($mime, true);
                $nameArchive = $params['type_content_id'] == '3' ? $name :  $this->getMime($mime, false) . date("Ymd_His") . ".$extension";
                $params['content'] = $params['content']->storeAs($route, $nameArchive);
            }

            $message = Messages::create($params);
            $message->fresh();

            DB::commit();
            return $this->successResponse('Mensaje enviado correctamente.');
        } catch (\Throwable $th) {
            if (Storage::exists($params['content'])) Storage::delete($params['content']);

            DB::rollBack();
            return $this->externalError('durante el envío del mensaje.', $th->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $validate = $this->verifyMessages($id);
            if (!$validate->original['status']) return $validate;

            $message = Messages::find($id);
            $message->update(['status' => 2]);

            if ($message->type_content_id != '1' && Storage::exists($message->content)) Storage::delete($message->content);

            DB::commit();
            return $this->successResponse('Mensaje eliminado satisfactoriamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->externalError('durante la eliminación del mensaje.', $th->getMessage());
        }
    }

    private function verifyMessages($id)
    {
        $message = Messages::activeForID($id)->first();
        if (!$message) return $this->errorResponse('El mensaje seleccionado no esta disponible', 400);

        $validateParticipant = $this->validateParticipant($message->conversation_id);
        if (!$validateParticipant->original['status']) return $validateParticipant;

        return $this->successResponse('OK');
    }

    private function getMime($mime, $route)
    {
        if (strpos($mime, 'image') !== false) {
            return $route ? "/Image" : "IMG-";
        } elseif (strpos($mime, 'video') !== false) {
            return $route ? "/Video" : "VID-";
        } else if ($route){
            return '/Archive';
        } else {
            return null;
        }
    }

    private function validateParticipant($conversation_id)
    {
        $participant = Participants::participant($conversation_id, $this->userJwt->id)->active()->first();
        if (!$participant) return $this->errorResponse('No perteneces al chat', 400);

        return $this->successResponse('OK');
    }
}
