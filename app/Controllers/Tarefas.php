<?php

namespace App\Controllers;

class Tarefas extends BaseController
{
    public function index()
    {
        $data = [
            'permission' => ['processos'=>true, 'intimacoes'=>true, 'movimentos'=>true],
            'img'       =>  'vazio.png',
            'titulo'    => 'Dashboard'
        ];
        $tarefas = $this->request->getGet();
        print_r($tarefas);
    }

    


}
