<?php

namespace App\Services;

use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Traits\HasResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersService
{
    use HasResponse;
    private $userJwt;

    public function __construct()
    {
        $this->userJwt = JWTAuth::user();
    }

    public function list($withPagination)
    {
        $users = User::userFilters();

        $users = !empty($withPagination)
            ? $users->paginate($withPagination['perPage'], page: $withPagination['page'])
            : $users->get();

        $users = UsersResource::collection($users);

        return $this->successResponse('Lectura exitosa.', $users);
    }

    public function store($params)
    {
        DB::beginTransaction();
        try {
            $validateEmail = User::email($params['email'])->active()->first();
            if($validateEmail) return $this->errorResponse('El correo ya se encuentra registrado', 400);

            $params['img_profile'] = 'public/profile/default/user.png';

            $user = User::create($params);
            $user->fresh();

            DB::commit();
            return $this->successResponse('Te registraste correctamente.', $user);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->externalError('durante la creación de tu usuario.', $th->getMessage());
        }
    }

    public function update($params)
    {
        DB::beginTransaction();
        try {
            $validate = $this->verifyUser($this->userJwt->id, $params['email']);
            if (!$validate->original['status']) return $validate;

            $user = User::find($this->userJwt->id);
            $user->update($params);

            DB::commit();
            return $this->successResponse('Tus datos fueron actualizados correctamente.', $user);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->externalError('durante la actualización del usuario.', $th->getMessage());
        }
    }

    public function delete()
    {
        DB::beginTransaction();
        try {
            $validate = $this->verifyUser($this->userJwt->id);
            if (!$validate->original['status']) return $validate;

            $user = User::find($this->userJwt->id);
            $user->update(['status' => 2]);

            DB::commit();
            return $this->successResponse('Eliminaste tu usuario satisfactoriamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->externalError('durante la eliminación de tu usuario.', $th->getMessage());
        }
    }

    public function updateImage($params)
    {
        DB::beginTransaction();
        try {
            $validate = $this->verifyUser($this->userJwt->id);
            if (!$validate->original['status']) return $validate;

            $user = User::find($this->userJwt->id);

            // Validar si el usuario ya tiene una imagen de perfil
            if ($user->img_profile  && $user->img_profile != "public/profile/default/user.png" && Storage::exists($user->img_profile)) {
                Storage::delete($user->img_profile);
            }

            $route = "public/profile/$user->id";
            $nameImg = "$user->id-" . date("Ymd_His") . "." . $params['img_profile']->getClientOriginalExtension();
            $img_profile = $params['img_profile']->storeAs($route, $nameImg);

            $user->update(['img_profile' => $img_profile]);

            DB::commit();
            return $this->successResponse('Foto de perfil actualizada satisfactoriamente.', $user);
        } catch (\Throwable $th) {
            if (isset($img_profile) && Storage::exists($img_profile)) {
                Storage::delete($img_profile);
            }

            DB::rollBack();
            return $this->externalError('durante la actualización de tu foto de perfil.', $th->getMessage());
        }
    }

    private function verifyUser($id, $email = null)
    {
        $user = User::activeForID($id)->first();
        if (!$user) return $this->errorResponse('Tu usuario no está disponible', 400);

        if(isset($email)){
            $validateEmail = User::email($email, $id)->active()->first();
            if ($validateEmail) return $this->errorResponse('El correo ya se encuentra registrado.', 400);
        }

        return $this->successResponse('OK');
    }
}
