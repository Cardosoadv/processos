<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;

class Index extends BaseController
{
    public function index()
    {
        $data = [
            'titulo' => 'Financeiro',
        ];
        $conta_id = 1;

        // Instanciar os models
        $despesasModel = model('Financeiro/FinanceiroPagtoDespesasModel');
        $receitasModel = model('Financeiro/FinanceiroPagtoReceitasModel');

        // Calcular o total de despesas para a conta específica
        $totalDespesas = $despesasModel ->where('conta_id', $conta_id)
                                        ->selectSum('valor')
                                        ->get()
                                        ->getRowArray()['valor'];

        // Calcular o total de receitas para a conta específica
        $totalReceitas = $receitasModel ->where('conta_id', $conta_id)
                                        ->selectSum('valor')
                                        ->get()
                                        ->getRowArray()['valor'];

        // Calcular o saldo (Receitas - Despesas)
        $data['saldo'] = $totalReceitas - $totalDespesas;

        return view('financeiro/index', $data);
    }
}