<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cantores;
use App\Models\Albuns;
use App\Http\Middleware\Jwt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConsultasController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login','register']]);
    }

    //endpoint para buscas cantores
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

        //verificando o resultado
        if(!empty(json_decode($buscabanco))){
            return response()->json(['error' => 'Nenhum resultado encontrado!'], 200);
        }

        return response()->json($buscabanco, 200);
    }

    //endpoint para buscas cantores e albuns
    public function buscaCantoresAlbuns(Request $request)
    {
        //definindo as mensagem de erros
        $messages = [
            'name' => 'Campo inválido.',
            'ordem' => 'Campo inválido.',
            'limit' => 'Campo inválido.',
        ];
        //verificando os parametros enviados
         $validator =  Validator::make($request->all(), [
              'name' => ['string', 'max:255'],
              'ordem' => ['string', 'max:255'],
              'limit' => ['integer',],
         ],$messages);

         //verificando se encontra erros nos paramtros enviados
         if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
         }

         //montando a consuta sql 
        $busca = Cantores::with(['Albuns']);
                 
        if(isset($request['name'])){
            $busca = $busca->where('cantores.name', $request['name']);
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

        //verificando o resultado
        $teste = json_decode(json_encode($buscabanco));
        if(empty($teste->data)){
            return response()->json(['error' => 'Nenhum resultado encontrado!'], 200);
        }

        return response()->json($buscabanco, 200);

    }
    //endpoint para buscas cantores e img
    public function buscaAlbunsImg(Request $request)
    {
        //definindo as mensagem de erros
        $messages = [
            'name' => 'Campo inválido.',
            'name.required' => 'O NAME do Album é obrigatório.',
        ];
        //verificando os parametros enviados
         $validator =  Validator::make($request->all(), [
              'name' => ['required','string', 'max:255'],
         ],$messages);

         //verificando se encontra erros nos paramtros enviados
         if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
         }

        //verificando se existe album 
        $img = DB::table('imagens')->where('name_album', $request['name'])->get();
        if(empty($img[0]->cantores_id)){
            return response()->json(['error' => 'Não contém nenhum album com esse nome!'], 200);
        }
        
        //fazendo o retorno
        $nomecantor =  DB::table('cantores')->where('id', $img[0]->cantores_id)->get();
        $retorno['nome_cantor']     =  $nomecantor[0]->name;
        $retorno['cantor_id']       =  $nomecantor[0]->id;
        $retorno['nome_album']      =  $request['name'];
        $retorno['quantidade_img']  =  count($img);
         
        $i = 1;
        foreach ($img as $value) {
            $url = Storage::disk('s3')->temporaryUrl(
                $value->imagem, now()->addMinutes(5),
                [
                    'ResponseContentType' => 'application/octet-stream',
                    'ResponseContentDisposition' => 'attachment; filename=' . $value->imagem,
                ]
            );
            $nomeimg = "link_img" . $i;
            $retorno[$nomeimg] = $url;
            $i++;
        }

        return response()->json($retorno, 200);
    }
}
