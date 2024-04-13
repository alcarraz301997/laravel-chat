<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Participants extends Model
{
    use HasFactory;
    protected $table = "tbl_participants";

    protected $fillable = [
        'conversation_id',
        'user_id',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    # Relations
    public function conversationType(): HasOne
    {
        return $this->hasOne(ConversationTypes::class, 'id', 'conversation_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
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

    public function scopeParticipant($query, $conversation, $user)
    {
        return $query->where('conversation_id', $conversation)->where('user_id', $user);
    }

    # Filtros
    public function scopeParticipantFilters($query)
    {
        #Filtro de Buscador
        $query->when(
            request('search'),
            fn ($query) => $query->where('name', 'LIKE', '%' . request('search') . '%')
        );

        #Filtro de id de conversación
        $query->when(
            request('conversation_id'),
            fn ($query) => $query->where('conversation_id', request('conversation_id'))
        );

        #Filtro de id de usuario
        $query->when(
            request('user_id'),
            fn ($query) => $query->where('user_id', request('user_id'))
        );

        #Filtro de estados
        $query->when(
            request('status') !== null,
            fn ($query) => $query->where('status', request('status'))
        )->when(
            request('status') === null,
            fn ($query) => $query->active()
        );
    }
}
