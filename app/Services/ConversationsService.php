<?php

namespace App\Services;

use App\Http\Resources\ConversationsResource;
use App\Models\Conversations;
use App\Models\Participants;
use App\Models\User;
use App\Traits\HasResponse;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class ConversationsService
{
    use HasResponse;
    private $userJwt;

    public function __construct()
    {
        $this->userJwt = JWTAuth::user();
    }

    public function list($withPagination)
    {
        $conversations = Conversations::conversationFilters();

        $conversations = !empty($withPagination)
            ? $conversations->paginate($withPagination['perPage'], page: $withPagination['page'])
            : $conversations->get();

        $conversations = ConversationsResource::collection($conversations->load('type'));

        return $this->successResponse('Lectura exitosa.', $conversations);
    }

    public function listConversation($id)
    {
        $conversations = Conversations::activeForID($id)->first()->load('type');

        $conversations = ConversationsResource::make($conversations);

        return $this->successResponse('Lectura exitosa.', $conversations);
    }

    public function store($params)
    {
        DB::beginTransaction();
        try {
            if($params['type_id'] == '1') {
                $validateChat = Conversations::where('user_created', $this->userJwt->id)->where('type_id', 1)
                ->whereHas('participants', function ($query) use ($params) {
                    $query->where('user_id', $params['user_id'])->orWhere('user_id', $this->userJwt->id);
                }, '=', 2)->active()->first();

                if($validateChat) return $this->successResponse('OK', $validateChat->id);

                $participants = [$params['user_id'], $this->userJwt->id];
                $message = 'chat';
            } else {
                $participants = $params['users_id'];
                if (in_array($this->userJwt->id, $participants)) return $this->errorResponse('No debes volver a seleccionarte.', 400);

                $participants[] = $this->userJwt->id;

                $participants = User::whereIn('id', $participants)->active()->pluck('id');

                $message = 'grupo';
            }

            $conversations = Conversations::create([
                'name' => $params['name'] ?? null,
                'type_id' => $params['type_id'],
                'user_created' => $this->userJwt->id
            ]);
            $conversations->fresh();

            $dataParticipants = [];
            foreach ($participants as $participant) {
                $dataParticipants[] = [
                    'conversation_id' => $conversations->id,
                    'user_id' => $participant,
                    'created_at' => $conversations->created_at,
                    'updated_at' => $conversations->updated_at
                ];
            }

            if(!empty($dataParticipants)) Participants::insert($dataParticipants);

            DB::commit();
            return $this->successResponse("Creaste correctamente un $message.", $conversations->id);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->externalError("durante la creación de un chat.", $th->getMessage());
        }
    }

    public function updateName($id, $params)
    {
        DB::beginTransaction();
        try {
            $validate = $this->verifyConversations($id, $params['name']);
            if (!$validate->original['status']) return $validate;

            $conversation = Conversations::find($id);
            $conversation->update(['name' => $params['name']]);

            DB::commit();
            return $this->successResponse('El nombre del grupo fue actualizado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->externalError('durante la actualización del nombre del grupo.', $th->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $validate = $this->verifyConversations($id);
            if (!$validate->original['status']) return $validate;

            $conversation = Conversations::find($id);
            $conversation->update(['status' => 2]);

            $participant = Participants::where('conversation_id', $conversation->id);
            $participant->update(['status' => 2]);

            DB::commit();
            return $this->successResponse('Eliminaste el chat.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->externalError('durante la eliminación de tu usuario.', $th->getMessage());
        }
    }

    private function verifyConversations($id, $name = null)
    {
        $conversation = Conversations::activeForID($id)->first();
        if (!$conversation) return $this->errorResponse('El chat no está disponible', 400);

        $participants = Participants::participant($conversation->id, $this->userJwt->id)->active()->first();
        if (!$participants) return $this->errorResponse('No tienes acceso a este chat', 400);

        if(isset($name) && $conversation->type_id == '1') return $this->errorResponse('Este chat no es grupo para cambiar de nombre.', 400);

        return $this->successResponse('OK');
    }
}
