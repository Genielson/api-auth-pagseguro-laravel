<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
class AuthController extends BaseController

{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','signup']]);
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
     * @return App\Traits\Iluminate\Http\Response|App\Traits\Iluminate\Http\JsonResponse|void
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        if ($this->isRegisterValid($request)) {
            try {
                $user = new User();
                $user->password = $request->password;
                $user->email = $request->email;
                $user->name = $request->name;
                $user->save();
                return $this->successResponse($user);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
    }




    /**
     * Login
     *
     * @return void
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
        return $this->respondWithToken($token);
    }

    public function signup(Request $request)
    {
        // Regras de validação
        $rules = [
            'nome' => 'required',
            'email' => 'required|email',
            'senha' => 'required',
        ];

        // Mensagens de erro personalizadas
        $messages = [
            'nome.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um endereço de e-mail válido.',
            'senha.required' => 'O campo senha é obrigatório.',
        ];

        // Validar os dados
        $validator = Validator::make($request->all(), $rules, $messages);

        // Verificar se a validação falhou
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

           // Criar o usuário
           $user = User::create([
            'name' => $request->input('nome'),
            'email' => $request->input('email'),
            'password' => ($request->input('senha')),
        ]);

        // Retornar a resposta com sucesso e o token
        return response()->json(['success' => true],  200);

    }


    /**
     * refresh
     *
     * @return void
     */
    public function refresh()
    {
        $token = auth()->refresh();
        return $this->respondWithToken($token);
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Logout with success!'
        ], 401);
    }

    /**
     * Undocumented function
     *
     * @param [type] $token
     * @return void
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 // default 1 hour
        ]);
    }
}
