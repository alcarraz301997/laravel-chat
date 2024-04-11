<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversations extends Model
{
    use HasFactory;
    protected $table = "tbl_conversations";

    protected $fillable = [
        'name',
        'type_id',
        'status'
    ];

    # Relations
    public function type(): HasOne
    {
        return $this->hasOne(ConversationTypes::class, 'id', 'type_id');
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
    public function scopeConversationFilters($query)
    {
        #Filtro de Buscador
        $query->when(
            request('search'),
            fn ($query) => $query->where('name', 'LIKE', '%' . request('search') . '%')
        );

        #Filtro de tipo de conversación
        $query->when(
            request('type_id'),
            fn ($query) => $query->where('type_id', request('type_id'))
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
