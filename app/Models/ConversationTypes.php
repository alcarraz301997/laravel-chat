<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationTypes extends Model
{
    use HasFactory;
    protected $table = "tbl_conversation_types";

    protected $fillable = [
        'name',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    # Query scopes
    public function scopeActiveForID($query, $id)
    {
        return $query->where('id', $id)->active();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeName($query, $name, $id = null)
    {
        return $query->when($id, fn ($query) => $query->where('id', '<>', $id))->where('name', $name);
    }

    # Filtros
    public function scopeConversationTypeFilters($query)
    {
        #Filtro de Buscador
        $query->when(
            request('search'),
            fn ($query) => $query->where('name', 'LIKE', '%' . request('search') . '%')
        );

        #Filtro de nombres
        $query->when(
            request('name'),
            fn ($query) => $query->where('name', 'LIKE', '%' . request('name') . '%')
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
