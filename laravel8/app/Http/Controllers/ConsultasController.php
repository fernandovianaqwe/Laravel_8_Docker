<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Middleware\Jwt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ConsultasController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login','register']]);
    }

    //endpoint para cadastar cantores
    public function buscaCantores(Request $request)
    {
        //definindo as mensagem de erros
        $messages = [
            'name' => 'Campo inválido.',
            'estilo' => 'Campo inválido.',
            'id' => 'Campo inválido.',
            'ordem' => 'Campo inválido.',
            'limit' => 'Campo inválido.',
        ];
        //verificando os parametros enviados
         $validator =  Validator::make($request->all(), [
              'name' => ['string', 'max:255'],
              'estilo' => ['string', 'max:255'],
              'id' => ['integer',],
              'ordem' => ['string', 'max:255'],
              'limit' => ['integer',],
         ],$messages);

         //verificando se encontra erros nos paramtros enviados
         if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
         }
        

        //montando a consuta sql 
        $busca = DB::table('cantores');
        if(isset($request['name'])){
            $busca = $busca->where('name', $request['name']);
        }
        if(isset($request['estilo'])){
            $busca = $busca->where('estilos', $request['estilo']);
        }
        if(isset($request['id'])){
            $busca = $busca->where('id', $request['id']);
        }
        if($request['ordem'] == "asc" || $request['ordem'] == "desc"){
            $busca = $busca->orderBy('name', $request['ordem']);
        }
        if(isset($request['limit'])){
            $limit = $request['limit'];
        }else{
            $limit = 5;
        }
        //fazendo a consulta no banco 
        $buscabanco = $busca->paginate($limit);
            
        return response()->json($buscabanco, 200);
    }
}
