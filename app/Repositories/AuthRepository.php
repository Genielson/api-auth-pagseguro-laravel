<?php

namespace App\Repositories;

use App\Http\Contracts\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\Exception;

class AuthRepository implements AuthRepositoryInterface
{

    public function getUserLogin(Request $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $token = auth()->setTTL(env('JWT_TTL', '60'))->attempt($credentials);
            if ($token) {
                return $this->respondWithToken($token);
            } else {
                return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
            }
        }catch (Exception $e){
            return response()->json(['mensagem'=> 'Houve um erro'],500);
        }
    }

    public function userRegister(Request $request)
    {
        // TODO: Implement userRegister() method.
    }

    public function getUserInfo()
    {
        // TODO: Implement getUserInfo() method.
    }
}
