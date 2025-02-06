<?php

namespace App\Controllers;

use App\Database\Migrations\ProcessosPartes;
use App\Libraries\ReceberIntimacoesJs;


class Testes extends BaseController
{
    public function index()
    {
        $model = model('ProcessoObjetoModel');
        $objeto = $model->listarObjetoProcesso(2);
        echo "<pre>";
        print_r($objeto);
    }

    public function processarIntimacoes()
    {

        $lib = new ReceberIntimacoesJs(); 

        $json = $this->request->getJSON();

        $resposta = $lib->getIntimacoes($json);
        return $this->response->setJSON($resposta);
        
    }
}