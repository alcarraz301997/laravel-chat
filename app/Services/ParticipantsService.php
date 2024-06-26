<?php

namespace App\Services;

use App\Http\Resources\ParticipantsResource;
use App\Models\Conversations;
use App\Models\Participants;
use App\Traits\HasResponse;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class ParticipantsService
{
    use HasResponse;
    private $userJwt;

    public function __construct()
    {
        $this->userJwt = JWTAuth::user();
    }

    // public function list($withPagination)
    // {
    //     $participants = Participants::participantFilters();

    //     $participants = !empty($withPagination)
    //         ? $participants->paginate($withPagination['perPage'], page: $withPagination['page'])
    //         : $participants->get();

    //     $participants = ParticipantsResource::collection($participants->load('conversationType', 'user'));

    //     return $this->successResponse('Lectura exitosa.', $participants);
    // }

    public function store($params)
    {
        DB::beginTransaction();
        try {
            $conversation = Conversations::activeForID($params['conversation_id'])->where('type_id', 2)->first();
            if(!$conversation) return $this->errorResponse('El chat no es grupo para agregar personas.', 403);

            $participant = Participants::create($params);
            $participant->fresh();

            DB::commit();
            return $this->successResponse("Agregado al grupo correctamente.", $participant);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->externalError("durante la incorporación al grupo.", $th->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $validate = Participants::activeForID($id)->first();
            if (!$validate) return $this->errorResponse('Participante seleccionado no disponible', 400);

            if($validate->user_id == $this->userJwt->id) {
                $messageSuccess = 'Saliste del grupo.';
            } else {
                $messageSuccess = 'Eliminaste a un miembro del grupo.';
            }

            $conversation = Conversations::activeForID($validate->conversation_id)->where('type_id', 2)->first();
            if(!$conversation) return $this->errorResponse('El chat no es grupo.', 403);

            $participant = Participants::find($id);
            $participant->update(['status' => 2]);

            DB::commit();
            return $this->successResponse($messageSuccess);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->externalError('durante la eliminación de un miembro.', $th->getMessage());
        }
    }
}
