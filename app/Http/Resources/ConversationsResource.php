<?php

namespace App\Http\Resources;

use App\Models\MessageView;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tymon\JWTAuth\Facades\JWTAuth;

class ConversationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = $this->relationLoaded('type') ? $this->whenLoaded('type') : null;
        $user = $this->relationLoaded('user') ? $this->whenLoaded('user') : null;
        $participants = $this->relationLoaded('participants') ? $this->whenLoaded('participants') : null;

        return [
            'id'=>$this->id,
            'name' => $this->type_id == 2 ? $this->name : $this->user->name . " " . $this->user->surname,
            'type_id' => $this->type_id,
            $this->mergeWhen($type, fn() => [
                'type' => $type->name,
            ]),
            'user_created' => $this->user_created,
            $this->mergeWhen($user, fn() => [
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->email,
            ]),
            $this->mergeWhen($participants, fn() => [
                'participants' => ParticipantsResource::collection($participants->load('user')),
            ]),
            'not_view' => $this->countNotView($this->id),
            'status'=>$this->status
        ];
    }

    public function countNotView($conversation_id)
    {
        return MessageView::whereHas('message', fn ($query) => $query->where('conversation_id', $conversation_id))
            ->whereHas('participant', fn ($query) => $query->where('user_id', JWTAuth::user()->id))
            ->where('date_seen', null)->active()->count();

    }
}
