<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Testes extends BaseController
{
    public function index()
    {
        $permission['processos']    = (auth()->user()->can('module.processos'));
        $permission['pessoas']      = (auth()->user()->can('module.pessoas'));
        $permission['tarefas']      = (auth()->user()->can('module.tarefas'));
        $permission['financeiro']   = (auth()->user()->can('module.financeiro'));
        $permission['intimacoes']   = (auth()->user()->can('module.intimacoes'));
        echo '<pre>';
        print_r($permission);
        echo '</pre>';
    }


    public function testes(){
        $data = [
            'titulo'    => 'Consultar Processo',
            'anotacoes' => [ 'anotacoes' => [] ],
            'data'      => [],
        ];
        $data['img'] = 'vazio.png';
        return view('testes', $data);
    }

}
