<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Messages extends Model
{
    use HasFactory;
    protected $table = "tbl_messages";

    protected $fillable = [
        'conversation_id',
        'user_id',
        'content',
        'type_content_id',
        'status'
    ];

    protected $hidden = [
        //
    ];

    # Relations
    public function conversationType(): HasOne
    {
        return $this->hasOne(ConversationTypes::class, 'id', 'conversation_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function contentType(): HasOne
    {
        return $this->hasOne(ContentTypes::class, 'id', 'type_content_id');
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
    public function scopeMessageFilters($query)
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

        #Filtro de usuario
        $query->when(
            request('user_id'),
            fn ($query) => $query->where('user_id', request('user_id'))
        );

        #Filtro de tipo de contenido
        $query->when(
            request('type_content_id'),
            fn ($query) => $query->where('type_content_id', request('type_content_id'))
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
