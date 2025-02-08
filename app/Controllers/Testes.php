<?php

namespace App\Controllers;

use App\Database\Migrations\ProcessosPartes;
use App\Libraries\ReceberIntimacoesJs;
use App\Models\Financeiro\FinanceiroDespesasModel;

class Testes extends BaseController
{
    public function index()
    {
        $despesa = [
            'despesa'               => 'Conta de luz',
            'vencimento_dt'         => date('Y-m-d',strtotime('2021-12-31')),
            'valor'                 => 100.00,
            'categoria'             => 1,
            'fornecedor'            => 1,
            'comentario'            => 'Comentário opcional',
            'rateio'                => [
                [
                    'id' => 1,
                    'valor' => 50.00,
                ],
                [
                    'id' => 2,
                    'valor' => 50.00,
                ],
            ],
        ];
        $model = model('Financeiro/FinanceiroDespesasModel')->insert($despesa);

        return view('dashboard', $despesa);

    }

    public function exibirdespesas()
    {
        $model = model('Financeiro/FinanceiroDespesasModel')->findAll();
        echo '<pre>';
        print_r($model);
        
    }
}