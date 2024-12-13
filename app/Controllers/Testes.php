<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\IntimacoesModel;
use App\Models\ProcessosModel;

class Testes extends BaseController
{
    public function index()
    {
        $data = [
            'permission' => ['processos'=>true, 'intimacoes'=>true, 'movimentos'=>true],
            'img'       =>  'vazio.png'
        ];
        return view('dashboard', $data);        
    }
}
