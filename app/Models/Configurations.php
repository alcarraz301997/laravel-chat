<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Configurations extends Model
{
    use HasFactory;
    protected $table = "tbl_configurations";

    protected $fillable = [
        'configuration_1',
        'configuration_2',
        'user_id',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    # Relations
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

    # Filtros
    public function scopeConfigurationFilters($query)
    {
        #Filtro de usuario
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
