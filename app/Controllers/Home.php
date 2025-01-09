<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'permission' => ['processos'=>true, 'intimacoes'=>true, 'movimentos'=>true],
            'img'       =>  'vazio.png',
            'titulo'    => 'Dashboard'
        ];
        $data['responsaveis'] = model('ResposavelModel')->getUsers();
        Session()->set(['msg'=> null]);
        return view('dashboard', $data);
    }

    


}
