<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ParticipantsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $conversationType = $this->relationLoaded('conversationType') ? $this->whenLoaded('conversationType') : null;
        $user = $this->relationLoaded('user') ? $this->whenLoaded('user') : null;

        return [
            'id'=>$this->id,
            'conversation_id' => $this->conversation_id,
            $this->mergeWhen($conversationType, fn() => [
                'name' => $conversationType->name,
                'type_id' => $conversationType->type_id
            ]),
            'user_id' => $this->user_id,
            $this->mergeWhen($user, fn() => [
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->email,
                'email' => $user->email,
                'address' => $user->address,
                'phone' => $user->phone,
                'birthday' => $user->birthday,
                'img_profile' => Storage::url($user->img_profile)
            ]),
            'status'=>$this->status
        ];
    }
}
