<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'name' => $this->name,
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
            'status'=>$this->status
        ];
    }
}
