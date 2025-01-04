<?php

namespace App\Controllers;

use App\Libraries\ReceberIntimacoesJs;


class Testes extends BaseController
{
    public function index()
    {
        $apiUrl = 'https://comunicaapi.pje.jus.br/api/v1/comunicacao';
        $params = [
            'numeroOab' => '164136',
            'ufOab' => 'mg'
        ];
        $query = http_build_query($params);
        $apiUrl .= '?' . $query;
        $data['apiUrl'] = $apiUrl;
        // Iniciando a sessão cURL
        return view('receberintimacoesjs2', $data);
    }

    public function processarIntimacoes()
    {

        $lib = new ReceberIntimacoesJs(); 

        $json = $this->request->getJSON();

        $resposta = $lib->getIntimacoes($json);
        return $this->response->setJSON($resposta);
        
    }
}