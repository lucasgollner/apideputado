<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
//use GuzzleHttp\Message\Request;
//use GuzzleHttp\Message\Response;
use DB;
use App\Deputado;
use App\Redesocialdeputado;
use App\Verba;

class DeputadoController extends Controller
{
    //

    public function getDeputadosWS(){
    	//////////////////////////////////////////////////////////////////// BLOCO INTEGRANDO API VIA GUZZLE  /////////////////////////////////////////////////
    	// $client = new Client();
    	// $res = $client->get('http://dadosabertos.almg.gov.br/ws/deputados/em_exercicio?formato=json');

    	// $deputados = json_decode($res->getBody());
    	
    	// return $deputados;

    	//////////////////////////////////////////////////////////////////// BLOCO INTEGRANDO API VIA CURL //////////////////////////////////////////////////////
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://dadosabertos.almg.gov.br/ws/deputados/em_exercicio?formato=json",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 100,
		  CURLOPT_TIMEOUT => 600,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "",
		));

		$response = curl_exec($curl);
		$json = json_decode($response, true);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {	
		  return \Response::json(['Resultado' => 'Falha na conexão ao buscar lista telefonica dos DEPUTADOS'], 408);
		} else {
		  return $json;
		}
    }

    


    public function gravaDeputadosBD(){

    	$gravou = 0;
    	$atualizou = 0;
    	$falhou = 0;

    	$deps = $this->getDeputadosWS();

    	$totalDeps = sizeof($deps['list']);

    	// VERIFICA SE DEPUTADO JÁ ESTÁ NA BASE, CASO POSITIVO ATUALIZA INFORMAÇÕES, SENÃO REGISTRA O DEPUTADO
    	foreach($deps['list'] as $dep){
    		if($deputado = Deputado::where('numero', $dep['id'])->first()){
		    	$deputado->numero 			= $dep['id'];
		    	$deputado->nome 			= $dep['nome'];
		    	$deputado->partido 			= $dep['partido'];
		    	$deputado->tagLocalizacao 	= $dep['tagLocalizacao'];
		    	
		    	if($deputado->save())
		    		$atualizou++;
		    	else
		    		$falhou++;
    		}else{
    			$deputado = new Deputado;
		    	$deputado->numero 			= $dep['id'];
		    	$deputado->nome 			= $dep['nome'];
		    	$deputado->partido 			= $dep['partido'];
		    	$deputado->tagLocalizacao 	= $dep['tagLocalizacao'];
		    	
		    	if($deputado->save())
		    		$gravou++;
		    	else
		    		$falhou++;
    		}

    	}


    	return \Response::json(['Resultado' => ['Gravou' => $gravou, 'Atualizou' => $atualizou, 'Falhou' => $falhou, 'Total' => $totalDeps]], 201);

    }

    public function topDeputados(){

    	$res = Verba::select('idDeputado',DB::raw('SUM(valor) as total'))
    			->whereraw('year(dataReferencia) = 2017')
	            ->groupby('idDeputado')
	            ->orderby('total','desc')
	            ->paginate(5);

	    $result = [];
	    foreach($res as $valor){
	    	$dep = Deputado::where('numero',$valor->idDeputado)->first();
	    	array_push($result,["nome" => $dep->nome, "total" => $valor->total]);
	    }

    	
    	if($result)
    		return \Response::json(['Resultado' => $result], 200);
    	else
    		return \Response::json(['Resultado' => 'Vazio'], 404);
    }

}
