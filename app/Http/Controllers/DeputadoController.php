<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
//use GuzzleHttp\Message\Request;
//use GuzzleHttp\Message\Response;

class DeputadoController extends Controller
{
    //

    public function getDeputadosWS(){
    	$client = new Client();
    	$res = $client->get('http://dadosabertos.almg.gov.br/ws/deputados/em_exercicio?formato=json');

    	dd( json_decode($res->getBody()) );
    }
}
