<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cantores;
use App\Models\Albuns;
use App\Models\Imagens;
use App\Http\Middleware\Jwt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class InertController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login','register']]);
    }

    //function para cadastar cantores
    public function cadastrarCantores(Request $request)
    {
        //definindo as mensagem de erros
        $messages = [
            'name' => 'Campo inválido.',
            'name.required' => 'O Campo NAME é obrigatório.',
            'estilo' => 'Campo inválido.',
            'estilo.required' => 'O Campo ESTILO é obrigatório.',
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
    //function para cadastar albuns
    public function cadastraAlbuns(Request $request)
    {
        //definindo as mensagem de erros
        $messages = [
            'id' => 'Campo inválido.',
            'id.required' => 'O Campo ID é obrigatório.',
            'name' => 'Campo inválido.',
            'name.required' => 'O Campo NAME é obrigatório.',
        ];
        //verificando os parametros enviados
         $validator =  Validator::make($request->all(), [
              'name' => ['required', 'string', 'max:255'],
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
        //verificando se o album já foi cadastrado
        $album = DB::table('albuns')->where('name', $request['name'])->get();
        $teste = json_decode(json_encode($buscabanco));
        if(empty($teste->data)){
             return response()->json(['error' => 'Album já cadastrado!'], 200);
        }

        //adicionando as imagem na api s3
        if ($request->file('img1')) {
            $i = 1;
            //verificando a quantidade de imagem que cantor tem
            $qtdimg = DB::table('imagens')->where('cantores_id', $request['id'])->count();
            $qtdimg++;
            //loop de quantas imagem recebeu na requisição
            while ($request->file('img'.$i)) {
                $nomeimg = $request['id'] . "_" . $qtdimg;
                //formato de imagem permitidos
                $formatospermitidos = array( 'image/jpeg', 'image/png' );
                if (in_array($request->file('img'.$i)->getClientMimeType(), $formatospermitidos)) {
                    //verificando qual o formato png ou jpeg para dar o nome do arquivo
                    if($request->file('img'.$i)->getClientMimeType() == 'image/jpeg'){
                        $nomeimg = $request['id'] . "_" . $qtdimg .".jpeg";
                    }else{
                        $nomeimg = $request['id'] . "_" . $qtdimg .".png";
                    }
                    //insert na imagem na api s3
                    if (!Storage::disk('s3')->exists($nomeimg)) {
                         Storage::disk('s3')->putFileAs(
                             'mundomusic', $request->file('img'.$i), $nomeimg
                         );
                        //insert no banco na tebela imagens
                         Imagens::create([
                             'cantores_id' => $request['id'],
                             'imagem' => $nomeimg,
                         ]);
                    }
                    
                }else{
                    return response()->json(['error' => 'Imagem error, suporta somente JPEG e PNG!'], 200);
                }   

                $i++;
                $qtdimg++;  
            }
        }

        //insert no banco na tabela Albuns
         Albuns::create([
             'cantores_id' => $request['id'],
             'name' => $request['name'],
         ]);

        return response()->json(['message' => 'Cadastro do Album realizado com sucesso.']);
    }

}
