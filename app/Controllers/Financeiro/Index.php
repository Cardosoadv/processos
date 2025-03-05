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

    public function extrato($conta_id)
    {
        // Instanciar os models
        $despesasModel = model('Financeiro/FinanceiroPagtoDespesasModel');
        $receitasModel = model('Financeiro/FinanceiroPagtoReceitasModel');

        // Buscar despesas da conta
        $despesas = $despesasModel  ->where('conta_id', $conta_id)
                                    ->findAll();

        // Adicionar campo 'tipo' para identificar despesas
        foreach ($despesas as &$despesa) {
            $despesa['data'] = $despesa['pagamento_despesa_dt'];
            $despesa['tipo'] = 'despesa';
            $despesa['valor'] = -$despesa['valor']; // Despesas são valores negativos
        }

        // Buscar receitas da conta
        $receitas = $receitasModel  ->where('conta_id', $conta_id)
                                    ->findAll();

        // Adicionar campo 'tipo' para identificar receitas
        foreach ($receitas as &$receita) {
            $receita['data'] = $receita['pagamento_receita_dt'];
            $receita['tipo'] = 'receita';
        }

        // Unir despesas e receitas em um único array
        $extrato = array_merge($despesas, $receitas);

        // Ordenar extrato por data (ordem crescente)
        usort($extrato, function ($a, $b) {
            return strtotime($a['data']) - strtotime($b['data']);
        });

        // Calcular saldo acumulado linha a linha
        $saldoAcumulado = 0;
        foreach ($extrato as &$registro) {
            $saldoAcumulado += $registro['valor'];
            $registro['saldo'] = $saldoAcumulado;
        }

        echo '<pre>';
        print_r($extrato);

    }
}