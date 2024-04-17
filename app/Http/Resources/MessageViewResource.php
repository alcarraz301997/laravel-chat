<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MessageViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $participant = $this->relationLoaded('participant') ? $this->whenLoaded('participant') : null;

        return [
            'id' => $this->id,
            'message_id' => $this->message_id,
            'participant_id' => $this->participant_id,
            $this->mergeWhen($participant, fn() => [
                'user_id' => $participant->user_id,
                'name' => $participant->user->name,
                'surname' => $participant->user->surname,
                'email' => $participant->user->email,
                'address' => $participant->user->address,
                'phone' => $participant->user->phone,
                'birthday' => $participant->user->birthday,
                'img_profile' => Storage::url($participant->user->img_profile)
            ]),
            'date_send' => $this->date_send,
            'date_seen' => $this->date_seen,
            'status'=>$this->status
        ];
    }
}
