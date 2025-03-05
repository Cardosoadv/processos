<?php
namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;

class Index extends BaseController 
{
    public function extrato($conta_id)
    {
        // Instanciar os models
        $despesasModel = model('Financeiro/FinanceiroPagtoDespesasModel');
        $receitasModel = model('Financeiro/FinanceiroPagtoReceitasModel');

        // Buscar despesas da conta
        $despesas = $despesasModel->where('conta_id', $conta_id)
                                  ->findAll();

        // Adicionar campo 'tipo' para identificar despesas e calcular rateio
        foreach ($despesas as &$despesa) {
            $despesa['data'] = $despesa['pagamento_despesa_dt'];
            $despesa['tipo'] = 'despesa';
            $despesa['valor'] = -$despesa['valor']; // Despesas são valores negativos

            // Processar rateio para despesas
            $despesa['rateio_original'] = json_decode($despesa['rateio'], true);
        }

        // Buscar receitas da conta
        $receitas = $receitasModel->where('conta_id', $conta_id)
                                  ->findAll();

        // Adicionar campo 'tipo' para identificar receitas e calcular rateio
        foreach ($receitas as &$receita) {
            $receita['data'] = $receita['pagamento_receita_dt'];
            $receita['tipo'] = 'receita';

            // Processar rateio para receitas
            $receita['rateio_original'] = json_decode($receita['rateio'], true);
        }

        // Unir despesas e receitas em um único array
        $extrato = array_merge($despesas, $receitas);

        // Ordenar extrato por data (ordem crescente)
        usort($extrato, function ($a, $b) {
            return strtotime($a['data']) - strtotime($b['data']);
        });

        // Calcular saldo acumulado e rateio acumulado por ID
        $saldoAcumulado = 0;
        $rateioAcumuladoPorId = [];

        foreach ($extrato as &$registro) {
            // Calcular saldo acumulado
            $saldoAcumulado += $registro['valor'];
            $registro['saldo'] = $saldoAcumulado;

            // Calcular rateio acumulado por ID
            if (isset($registro['rateio_original'])) {
                $registro['rateio_detalhado'] = [];
                
                foreach ($registro['rateio_original'] as $rateio) {
                    $id = $rateio['id'];
                    $valor = floatval($rateio['valor']);

                    // Inicializar o acumulado para este ID se não existir
                    if (!isset($rateioAcumuladoPorId[$id])) {
                        $rateioAcumuladoPorId[$id] = 0;
                    }

                    // Ajustar o valor baseado no tipo de registro (subtrair para despesas)
                    $valorAjustado = $registro['tipo'] === 'despesa' ? -$valor : $valor;

                    // Acumular o valor para este ID
                    $rateioAcumuladoPorId[$id] += $valorAjustado;

                    // Adicionar ao rateio detalhado do registro
                    $registro['rateio_detalhado'][] = [
                        'id' => $id,
                        'valor' => $valor,
                        'acumulado' => $rateioAcumuladoPorId[$id]
                    ];
                }

                // Remover o rateio original para não poluir a saída
                unset($registro['rateio_original']);
            }
        }

        echo '<pre>';
        print_r($extrato);
    }
}