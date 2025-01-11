<?php

namespace App\Controllers;

use App\Libraries\ReceberIntimacoesJs;


class IntimacoesJs extends BaseController
{
    private $apiUrl = 'https://comunicaapi.pje.jus.br/api/v1/comunicacao';


    public function index()
    {
        $this->apiUrl;
        $params = [
            'numeroOab' => '164136',
            'ufOab' => 'mg'
        ];
        $query = http_build_query($params);
        $this->apiUrl .= '?' . $query;
        $data['apiUrl'] = $this->apiUrl;
        Session()->set(['msg'=> null]);
        return view('receberintimacoesjs', $data);
    }

    public function processarIntimacoes()
    {

        $lib = new ReceberIntimacoesJs(); 
        $json = $this->request->getJSON();
        $resposta = $lib->getIntimacoes($json);
        return $this->response->setJSON($resposta);
        
    }

    public function porNumeroProcesso()
    {
        $numeroProcesso = $this->request->getGet('numeroProcesso');
        $params = [
            'numeroProcesso' => $numeroProcesso,
        ];
        $this->apiUrl .= '?' . http_build_query($params);
        $data['apiUrl'] = $this->apiUrl;
        Session()->set(['msg'=> null]);
        return view('receberintimacoesjs', $data);
    }

    public function rodrigo()
    {
        $this->apiUrl;
        $params = [
            'numeroOab' => '61061',
            'ufOab' => 'mg'
        ];
        $query = http_build_query($params);
        $this->apiUrl .= '?' . $query;
        $data['apiUrl'] = $this->apiUrl;
        Session()->set(['msg'=> null]);
        return view('receberintimacoesjs', $data);
    }

}