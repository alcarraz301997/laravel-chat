<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MessageView extends Model
{
    use HasFactory;
    protected $table = "tbl_message_view";

    protected $fillable = [
        'message_id',
        'participant_id',
        'date_send',
        'date_seen',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    # Relations
    public function message(): HasOne
    {
        return $this->hasOne(Messages::class, 'id', 'message_id');
    }

    public function participant(): HasOne
    {
        return $this->hasOne(Participants::class, 'id', 'participant_id');
    }

    # Query scopes
    public function scopeActiveForID($query, $id)
    {
        return $query->where('id', $id)->active();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // public function scopeParticipant($query, $conversation, $user)
    // {
    //     return $query->where('conversation_id', $conversation)->where('user_id', $user);
    // }

    # Filtros
    // public function scopeMessageViewFilters($query)
    // {

    //     #Filtro de id de conversación
    // $query->when(
    //     request('conversation_id'),
    //     fn ($query) => $query->where('conversation_id', request('conversation_id'))
    // );

    //     #Filtro de usuario
    //     $query->when(
    //         request('user_id'),
    //         fn ($query) => $query->where('user_id', request('user_id'))
    //     );

    //     #Filtro de tipo de contenido
    //     $query->when(
    //         request('type_content_id'),
    //         fn ($query) => $query->where('type_content_id', request('type_content_id'))
    //     );

    //     #Filtro de estados
    //     $query->when(
    //         request('status') !== null,
    //         fn ($query) => $query->where('status', request('status'))
    //     )->when(
    //         request('status') === null,
    //         fn ($query) => $query->active()
    //     );
    // }
}
