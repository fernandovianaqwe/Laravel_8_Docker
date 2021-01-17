<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Middleware\Jwt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login','register']]);
    }

    //endpoint para cadastar cantores
    public function upateEstilo(Request $request)
    {
        //definindo as mensagem de erros
        $messages = [
            'id' => 'Campo inválido.',
            'id.required' => 'O Campo ID é obrigatório.',
            'estilo' => 'Campo inválido.',
            'estilo.required' => 'O Campo ESTILO é obrigatório.',
        ];
        //verificando os parametros enviados
         $validator =  Validator::make($request->all(), [
              'estilo' => ['required','string', 'max:255'],
              'id' => ['required','integer'],
         ],$messages);

         //verificando se encontra erros nos paramtros enviados
         if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
         }
        
        //verificando se existe cantor com o id 
        $cantor = DB::table('cantores')->where('id', $request['id'])->get();
        if(!json_decode($cantor)){
            return response()->json(['error' => 'Nenhum Cantor escontrado com o id!'], 200);
        }

        //executando o update 
        DB::table('cantores')->where('id', $request['id'])->update(['estilos' => $request['estilo']]);

            
        return response()->json(['message' => 'Update Realizado com sucesso.']);
    }
}
