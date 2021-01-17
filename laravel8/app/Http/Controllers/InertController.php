<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cantores;
use App\Http\Middleware\Jwt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InertController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login','register']]);
    }

    //endpoint para cadastar cantores
    public function cadastrarCantores(Request $request)
    {
        //definindo as mensagem de erros
        $messages = [
            'name' => 'Campo inválido.',
            'name.required' => 'O Campo NAME é obrigatório.',
            'estilo' => 'Campo inválido.',
            'estilo.required' => 'O Campo PASSWORD é obrigatório.',
        ];
        //verificando os parametros enviados
         $validator =  Validator::make($request->all(), [
              'name' => ['required', 'string', 'max:255'],
              'estilo' => ['required', 'string'],
         ],$messages);

         //verificando se encontra erros nos paramtros enviados
         if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
         }
         //verificar se o cantor existe com o estilo
         $existe = DB::table('Cantores')
                    ->where('name', $request['name'])
                    ->where('estilos', $request['estilo'])
                    ->get();           
        if(json_decode($existe)){
            return response()->json(['error' => 'Cantor já cadastrado!'], 200);
        }
        //insert no banco
         Cantores::create([
             'name' => $request['name'],
             'estilos' => $request['estilo'],
         ]);

        return response()->json(['message' => 'Cadastro do Cantor realizado com sucesso.']);
    }

}
