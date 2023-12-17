<?php

namespace App\Repositories;

use App\Http\Contracts\AuthRepositoryInterface;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\Exception;

class AuthRepository implements AuthRepositoryInterface
{
    use ApiResponser;
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
        try {
            $user = new User();
            $user->password = $request->password;
            $user->email = $request->email;
            $user->name = $request->name;
            $user->save();
            return $this->successResponse($user);
        }catch (Exception $e){
            return response()->json(['mensagem'=> 'Houve um erro'],500);
        }
    }

    public function getUserInfo()
    {
        try{
            $user = auth()->user();
            return $this->successResponse($user);
        }catch (Exception $e){
            return response()->json(['mensagem'=> 'Houve um erro'],500);
        }
    }
}
