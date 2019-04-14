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

class RedesocialdeputadoController extends Controller
{
    //
    public function getRedesSociaisWS(){
    	//////////////////////////////////////////////////////////////////// BLOCO INTEGRANDO API VIA GUZZLE  /////////////////////////////////////////////////////////////////
    	// $client = new Client();
    	// $res = $client->get('http://dadosabertos.almg.gov.br/ws/deputados/lista_telefonica?formato=json');

    	// $redes = json_decode($res->getBody());
    	
    	// dd ($redes);

    	//////////////////////////////////////////////////////////////////// BLOCO INTEGRANDO API VIA CURL ////////////////////////////////////////////////////////////////////
  		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://dadosabertos.almg.gov.br/ws/deputados/lista_telefonica?formato=json",
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

	public function gravaRedesSociaisDB(){
    		$contatos = $this->getRedesSociaisWS(); //dd($contatos);

    		foreach($contatos as $key => $infos){
    			foreach($infos as $key => $contato){
    				//dd($contato['id']);
	    			foreach($infos as $key => $contato){
		    			foreach($contato['redesSociais'] as $key => $redesSociais){
			    			if(!$reg = Redesocialdeputado::where('idDeputado', $contato['id'])->where('idRedeSocial', $redesSociais['redeSocial']['id'])->first()){
				    			$rede = new Redesocialdeputado;
				    			$rede->idDeputado 	= $contato['id'];
				    			$rede->idRedeSocial	= $redesSociais['redeSocial']['id'];
				    			$rede->nome 	 	= $redesSociais['redeSocial']['nome'];
				    			$rede->url 		 	= $redesSociais['redeSocial']['url'];

				    			$rede->save();
				    		}
			    		}
		    		}
	    		}
    		}

    	return \Response::json(['Resultado' => 'RedesSociais salvas'], 201);
    }

    public function topRedesSociais(){

    	$res = DB::table('redesocialdeputados')
    			->selectraw(DB::raw('COUNT(*) as total, idRedeSocial, ANY_VALUE(nome) as nome'))
	            ->groupby('idRedeSocial')
	            ->orderby('total','desc')
	            ->get();

	    if($res)
    		return \Response::json(['Resultado' => $res], 200);
    	else
    		return \Response::json(['Resultado' => 'Vazio'], 404);
    }
}
