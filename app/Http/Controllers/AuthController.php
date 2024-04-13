<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use App\Traits\HasResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HasResponse;

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            if ($validator->fails()) return $this->errorResponse($validator->errors(), 400);

            # Obtener token
            $credentials = request(['email', 'password']);

            $token = JWTAuth::attempt($credentials);
            if (!$token) return $this->errorResponse('Credenciales inválidas', 401);

            $user = JWTAuth::user();
            if($user->status != 1) return $this->successResponse('Tu cuenta se encuentra inactiva', 401);

            return $this->successResponse('OK', $this->respondWithToken($token, $user));
        } catch (\Throwable $th) {
            return $this->externalError('durante el login.', $th->getMessage());
            throw $th;
        }
    }

    protected function respondWithToken($token, $user)
    {
        try {
            return [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 2000,
                'user' => UsersResource::make($user)
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function me()
    {
        try {
            return $this->successResponse('OK', UsersResource::make(JWTAuth::user()));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
            return $this->successResponse('OK', 'Sesión cerrada con éxito');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function refresh()
    {
        try {
            $user = JWTAuth::user();

            $token_refresh = [
                'status' => true,
                'token' => $this->respondWithToken(JWTAuth::refresh(), $user),
            ];
            return $this->successResponse('OK', $token_refresh);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}