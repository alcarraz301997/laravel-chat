<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MessagesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $conversation = $this->relationLoaded('conversation') ? $this->whenLoaded('conversation') : null;
        $user = $this->relationLoaded('user') ? $this->whenLoaded('user') : null;
        $contentType = $this->relationLoaded('contentType') ? $this->whenLoaded('contentType') : null;
        $messageView = $this->relationLoaded('messageView') ? $this->whenLoaded('messageView') : null;

        return [
            'id'=>$this->id,
            'conversation_id' => $this->conversation_id,
            $this->mergeWhen($conversation, fn() => [
                'conversation' => $conversation->type_id == 2 ? $conversation->name : $conversation->user->name . " " . $conversation->user->surname,
                'type_conversation_id' => $conversation->type_id,
                'type_conversation' => $conversation->type->name,
                'user_created' => $conversation->type->user_created,
            ]),
            'user_id' => $this->user_id,
            $this->mergeWhen($user, fn() => [
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->email,
            ]),
            'content' => $this->type_content_id == 1 ? $this->content : Storage::url($this->content),
            'type_content_id' => $this->type_content_id,
            $this->mergeWhen($contentType, fn() => [
                'type_content' => $contentType->name,
            ]),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
            $this->mergeWhen($messageView, fn() => [
                'messageView' => MessageViewResource::collection($messageView->load('participant')),
            ]),
            'status'=>$this->status
        ];
    }
}
