<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "tbl_user";

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'address',
        'phone',
        'birthday',
        'img_profile',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    # Query scopes
    public function scopeActiveForID($query, $id)
    {
        return $query->where('id', $id)->active();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeEmail($query, $email, $id = null)
    {
        return $query->when($id, fn ($query) => $query->where('id', '<>', $id))->where('email', $email);
    }

    # Filtros
    public function scopeUserFilters($query)
    {
        #Filtro de Buscador
        $query->when(
            request('search'),
            fn ($query) => $query->where('name', 'LIKE', '%' . request('search') . '%')
                ->orWhere('surname', 'LIKE', '%' . request('search') . '%')
        );

        #Filtro de nombres
        $query->when(
            request('name'),
            fn ($query) => $query->where('name', 'LIKE', '%' . request('name') . '%')
        );

        #Filtro de apellidos
        $query->when(
            request('surname'),
            fn ($query) => $query->where('surname', 'LIKE', '%' . request('surname') . '%')
        );

        #Filtro de correo
        $query->when(
            request('email'),
            fn ($query) => $query->where('email', 'LIKE', '%' . request('email') . '%')
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
