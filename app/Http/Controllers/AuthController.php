<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\AuthRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Mockery\Exception;

/** @package App\Http\Controllers */
class AuthController extends Controller
{

    use ApiResponser;
    private $repository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $authRepository){
        $this->repository = $authRepository;
    }


    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isRegisterValid(Request $request)
    {
        return  $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:5'
            ]
        );
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isLoginValid(Request $request)
    {

        return $this->validate($request, [
            'email' => 'required|string',
            'password' =>  'required|string'
        ]);
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        try {
            if ($this->isLoginValid($request)) {
                return $this->repository->getUserLogin($request);
            }
        }catch (Exception $e){

        }
    }

    /**
     * @param Request $request
     * @return \App\Traits\Iluminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        if ($this->isRegisterValid($request)) {
            try {
                return $this->repository->userRegister($request);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function me(){
            try {

            }catch (Exception $e){

            }
    }
}
