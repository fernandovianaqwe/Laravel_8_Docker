<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Middleware\Jwt;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        //definindo as mensagem de erros
        $messages = [
            'email' => 'Campo inválido.',
            'email.email' => 'O Campo EMAIL deve conter um email válido.',
            'email.required' => 'O Campo EMAIL é obrigatório.',
            'password' => 'Campo inválido.',
            'password.required' => 'O Campo PASSWORD é obrigatório.',
            'password.min' => 'O Campo PASSWORD deve conter no minimo 5 caracter.',
        ];
        //verificando os parametros enviados
         $validator =  Validator::make($request->all(), [
              'email' => ['required', 'string', 'email', 'max:255'],
              'password' => ['required', 'string', 'min:5'],
         ],$messages);

        // //verificando se encontra erros nos paramtros enviados
         if ($validator->fails()) {
             return response()->json($validator->messages(), 200);
         }

        $credentials = request(['email', 'password']);
        
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email ou Password incorreto, Tente Novamente!'], 401);
        }

        return $this->respondWithToken($token);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Logout com sucesso.']);
    }

    //função para registro do sistema
    public function register(Request $request)
    {
        //definindo as mensagem de erros
        $messages = [
            'email' => 'Campo inválido.',
            'email.email' => 'O Campo EMAIL deve conter um email válido.',
            'email.required' => 'O Campo EMAIL é obrigatório.',
            'email.unique' => 'Email já cadastrado.',
            'password' => 'Campo inválido.',
            'password.required' => 'O Campo PASSWORD é obrigatório.',
            'name' => 'Campo inválido..',
            'name.required' => 'O Campo NAME é obrigatório.',
        ];
         //verificando os parametros enviados
         $validator =  Validator::make($request->all(), [
              'name' => ['required', 'string', 'max:255'],
              'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
              'password' => ['required', 'string', 'min:5', 'confirmed'],
         ],$messages);

        //verificando se encontra erros nos paramtros enviados
         if ($validator->fails()) {
             return response()->json($validator->messages(), 200);
         }

        //criando o usuario
       User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
         
        return response()->json(['message' => 'Cadastro Realizado com sucesso.']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
