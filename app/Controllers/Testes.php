<?php

namespace App\Controllers;

use App\Database\Migrations\ProcessosPartes;
use App\Libraries\ReceberIntimacoesJs;
use App\Models\Financeiro\FinanceiroDespesasModel;

class Testes extends BaseController
{
    public function index()
    {

    }

    public function exibirdespesas()
    {
        $model = model('Financeiro/FinanceiroDespesasModel')->findAll();
        echo '<pre>';
        print_r($model);
        
    }
}