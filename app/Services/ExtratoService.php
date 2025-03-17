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
// ExtratoService.php - Adicionando o novo método

    public function getExtratoPorPeriodo($conta_id, $dataInicial, $dataFinal)
    {
    // Converter as datas para o formato correto
    $dataInicial = date('Y-m-d', strtotime($dataInicial));
    $dataFinal = date('Y-m-d', strtotime($dataFinal));
    
    // 1. Obter todas as transações anteriores à data inicial para calcular o saldo anterior
    $despesasAnteriores = $this->extratoRepository->getDespesasAnteriores($conta_id, $dataInicial);
    $receitasAnteriores = $this->extratoRepository->getReceitasAnteriores($conta_id, $dataInicial);
    $transferenciasDe = $this->extratoRepository->getTransferenciasDeAnteriores($conta_id, $dataInicial);
    $transferenciasParaAnteriores = $this->extratoRepository->getTransferenciasParaAnteriores($conta_id, $dataInicial);
    
    // 2. Calcular o saldo anterior com rateio
    $saldoAnteriorRateio = [];
    
    // Processando rateio de despesas anteriores
    foreach ($despesasAnteriores as $despesa) {
        if (is_string($despesa['rateio'])) {
            $rateio = json_decode($despesa['rateio'], true);
            if (is_array($rateio)) {
                foreach ($rateio as $item) {
                    $id = $item['id'];
                    $valor = floatval(-$item['valor']);
                    
                    if (!isset($saldoAnteriorRateio[$id])) {
                        $saldoAnteriorRateio[$id] = 0;
                    }
                    
                    $saldoAnteriorRateio[$id] += $valor;
                }
            } else {
                // Se não tiver rateio específico, considerar valor total
                if (!isset($saldoAnteriorRateio['geral'])) {
                    $saldoAnteriorRateio['geral'] = 0;
                }
                $saldoAnteriorRateio['geral'] += floatval($despesa['valor']);
            }
        } else {
            // Se não tiver rateio, adicionar ao saldo geral
            if (!isset($saldoAnteriorRateio['geral'])) {
                $saldoAnteriorRateio['geral'] = 0;
            }
            $saldoAnteriorRateio['geral'] += floatval($despesa['valor']);
        }
    }
    
    // Processando rateio de receitas anteriores
    foreach ($receitasAnteriores as $receita) {
        if (is_string($receita['rateio'])) {
            $rateio = json_decode($receita['rateio'], true);
            if (is_array($rateio)) {
                foreach ($rateio as $item) {
                    $id = $item['id'];
                    $valor = floatval($item['valor']);
                    
                    if (!isset($saldoAnteriorRateio[$id])) {
                        $saldoAnteriorRateio[$id] = 0;
                    }
                    
                    $saldoAnteriorRateio[$id] += $valor;
                }
            } else {
                // Se não tiver rateio específico, considerar valor total
                if (!isset($saldoAnteriorRateio['geral'])) {
                    $saldoAnteriorRateio['geral'] = 0;
                }
                $saldoAnteriorRateio['geral'] += floatval($receita['valor']);
            }
        } else {
            // Se não tiver rateio, adicionar ao saldo geral
            if (!isset($saldoAnteriorRateio['geral'])) {
                $saldoAnteriorRateio['geral'] = 0;
            }
            $saldoAnteriorRateio['geral'] += floatval($receita['valor']);
        }
    }
    
    // Adicionar transferências ao saldo anterior (não possuem rateio)
    foreach ($transferenciasDe as $transf) {
        if (!isset($saldoAnteriorRateio['geral'])) {
            $saldoAnteriorRateio['geral'] = 0;
        }
        $saldoAnteriorRateio['geral'] += floatval($transf['valor']);
    }
    
    foreach ($transferenciasParaAnteriores as $transf) {
        if (!isset($saldoAnteriorRateio['geral'])) {
            $saldoAnteriorRateio['geral'] = 0;
        }
        $saldoAnteriorRateio['geral'] += floatval($transf['valor']);
    }
    
    // 3. Obter todas as transações do período
    $despesas = $this->extratoRepository->getDespesasPorContaPeriodo($conta_id, $dataInicial, $dataFinal);
    $receitas = $this->extratoRepository->getReceitasPorContaPeriodo($conta_id, $dataInicial, $dataFinal);
    $transferenciasDe = $this->extratoRepository->getTransferenciasDePorContaPeriodo($conta_id, $dataInicial, $dataFinal);
    $transferenciasParaAtual = $this->extratoRepository->getTransferenciasParaPorContaPeriodo($conta_id, $dataInicial, $dataFinal);
    
    // 4. Juntar todas as transações e ordenar por data
    $extrato = array_merge($despesas, $receitas, $transferenciasDe, $transferenciasParaAtual);
    
    usort($extrato, function($a, $b) {
        $dateA = strtotime($a['data']);
        $dateB = strtotime($b['data']);
        
        if ($dateA == $dateB) {
            return 0;
        }
        
        return ($dateA < $dateB) ? -1 : 1;
    });
    
    // 5. Calcular saldo corrente e rateio acumulado
    $saldoCorrente = array_sum($saldoAnteriorRateio);
    $rateioAcumuladoPorId = [];
    
    // Inicializar o rateio acumulado com o saldo anterior
    foreach ($saldoAnteriorRateio as $id => $valor) {
        $rateioAcumuladoPorId[$id] = ['rateio_acumulado' => $valor];
    }
    
    foreach ($extrato as &$transacao) {
        // Calcular saldo acumulado geral
        $saldoCorrente += $transacao['valor'];
        $transacao['saldo'] = $saldoCorrente;
        
        // Verificar se o rateio existe e é um array
        if (!isset($transacao['rateio']) || !is_array($transacao['rateio'])) {
            continue;
        }
        
        // Calcular rateio acumulado por ID
        foreach ($transacao['rateio'] as &$rateio) {
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
    
    return [
        'saldo_anterior' => $saldoAnteriorRateio,
        'saldo_anterior_total' => array_sum($saldoAnteriorRateio),
        'transacoes' => $extrato,
        'saldo_final' => $saldoCorrente,
        'rateio_acumulado' => $rateioAcumuladoPorId
    ];
}
}