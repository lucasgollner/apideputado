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

class VerbaController extends Controller
{
    //
    public function getVerbasWS($dep, $ano, $mes){

		set_time_limit(300); //função retira o limite de TIMEOUT do servidor (usando para testes)

    	//////////////////////////////////////////////////////////////////// BLOCO INTEGRANDO API VIA GUZZLE //////////////////////////////////////////////////////////////////
		//$client = new Client();
		//$res = $client->get('http://dadosabertos.almg.gov.br/ws/prestacao_contas/verbas_indenizatorias/deputados/'.$dep.'/'.$ano.'/'.$mes.'?formato=json');
		//$verbas = json_decode($res->getBody());
		//return $verbas;

    	//////////////////////////////////////////////////////////////////// BLOCO INTEGRANDO API VIA CURL ////////////////////////////////////////////////////////////////////
  		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://dadosabertos.almg.gov.br/ws/prestacao_contas/verbas_indenizatorias/deputados/".$dep."/".$ano."/".$mes."?formato=json",
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
		  return $this->getVerbasWS($dep, $ano, $mes);
		} else {
		  return $json;
		}

    }

	public function gravaVerbasBD(){

    	$deputados = Deputado::All(); 

    	//SELECIONA TODOS DEPUTADOS DA BASE E A CADA DEPUTADO BUSCA AS VERBAS INDENIZATORIAS PELO MÊS DO ANO SOLICITADO 2017, GRAVANDO NA TABELA ESSA VERBA BUSCADA PELA INTEGRAÇÃO
    	$cont = 0;
    	foreach($deputados as $deputado){

    		for($mes = 1; $mes <= 12; $mes++){

		    		$verbas = $this->getVerbasWS($deputado->numero, 2017, $mes); 

		    		foreach($verbas["list"] as $verba){

		    			$dataValor = [];
		    			foreach($verba["dataReferencia"] as $d){
		    				array_push($dataValor, $d);
		    			}

		    			if( !Verba::where('idDeputado', $deputado->numero)->where('codTipoDespesa', $verba["codTipoDespesa"])->first() ){
			    			$nova = new Verba;
			    			$nova->idDeputado 		= $deputado->numero;
					    	$nova->dataReferencia 	= $dataValor[1];
					    	$nova->codTipoDespesa 	= $verba["codTipoDespesa"];
					    	$nova->valor 			= $verba["valor"];
					    	$nova->descTipoDespesa 	= $verba["descTipoDespesa"];
					    	
					    	$nova->save();
					    }

		    		}
					sleep(1);

   			}
   			$cont++;
    	}    	

    	return \Response::json(['Resultado' => 'Total deputados com verbas salvas: '.$cont], 200);

    }
}
