<?php

namespace App\Services;

use App\Repositories\ExtratoRepository;

class ExtratoService
{
    protected $extratoRepository;

    public function __construct()
    {
        $this->extratoRepository = new ExtratoRepository();
    }

    public function getExtratoPorConta($conta_id)
    {
        $despesas = $this->extratoRepository->getDespesasPorConta($conta_id);
        $receitas = $this->extratoRepository->getReceitasPorConta($conta_id);
        $transferenciasDe = $this->extratoRepository->getTransferenciasDePorConta($conta_id);
        $transferenciasPara = $this->extratoRepository->getTransferenciasParaPorConta($conta_id);

        $extrato = array_merge($despesas, $receitas, $transferenciasDe, $transferenciasPara);

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

            // Verificar se o rateio existe e é um array
            if (!isset($registro['rateio']) || !is_array($registro['rateio'])) {
                continue;
            }

            // Calcular rateio acumulado por ID
            foreach ($registro['rateio'] as &$rateio) {
                $id = $rateio['id'];

                // Inicializar o acumulado para este ID se não existir
                if (!isset($rateioAcumuladoPorId[$id])) {
                    $rateioAcumuladoPorId[$id] = ['rateio_acumulado' => 0];
                }

                $rateioAcumulado = $rateioAcumuladoPorId[$id]['rateio_acumulado'];
                $rateioAcumulado += $rateio['valor'];
                $rateioAcumuladoPorId[$id]['rateio_acumulado'] = $rateioAcumulado;
                $rateio['rateio_acumulado'] = $rateioAcumulado; // Adiciona o valor acumulado ao item do rateio
            }
        }

        return $extrato;
    }
}