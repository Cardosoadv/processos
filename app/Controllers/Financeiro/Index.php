<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Models\ResposavelModel;
use App\Services\ExtratoService;

class Index extends BaseController
{
    protected $extratoService;

    public function __construct()
    {
        $this->extratoService = new ExtratoService();
    }

    public function index()
    {
        
        $dataReferencia = $this->request->getGet('dataReferencia')??null;
        // Se a data não for fornecida, usar data atual
        if ($dataReferencia === null) {
            $dataReferencia = date('Y-m-d');
        }

        // Buscar todas as contas disponíveis
        $contaModel = model('Financeiro/FinanceiroContasModel');
        $contas = $contaModel->where('deleted_at is null')->findAll();

        // Inicializar arrays para armazenar resultados
        $saldosPorConta = [];
        $rateiosPorAdvogado = [];

        // Para cada conta, calcular o saldo e o rateio até a data de referência
        foreach ($contas as $conta) {
            $conta_id = $conta['id_conta'];

            // Calcular extrato até a data de referência (do início do sistema até a data de referência)
            $extratoResultado = $this->extratoService->getExtratoPorPeriodo($conta_id, '2000-01-01', $dataReferencia);

            // Armazenar o saldo final da conta
            $saldosPorConta[$conta_id] = [
                'conta_nome' => $conta['conta'],
                'saldo' => $extratoResultado['saldo_final']
            ];

            // Processar os valores de rateio para cada advogado
            if (isset($extratoResultado['rateio_acumulado'])) {
                foreach ($extratoResultado['rateio_acumulado'] as $advogado_id => $dados) {
                    // Se o advogado_id não for "geral", adicionar ao rateio do advogado
                    if ($advogado_id !== 'geral') {
                        if (!isset($rateiosPorAdvogado[$advogado_id])) {
                            $rateiosPorAdvogado[$advogado_id] = 0;
                        }

                        $rateiosPorAdvogado[$advogado_id] += $dados['rateio_acumulado'];
                    }
                }
            }
        }

        // Buscar nomes dos advogados
        $advogadoModel = new ResposavelModel();
        foreach ($rateiosPorAdvogado as $advogado_id => &$valor) {
            $advogado = $advogadoModel->where('id',$advogado_id)->first();
            log_message('debug', 'Advogado ID: ' . $advogado_id . ', Nome: ' . $advogado['username']);
            $nome = $advogado ? $advogado['username'] : 'Advogado #' . $advogado_id;

            // Transformar o valor em um array com nome e valor
            $valor = [
                'nome' => $nome,
                'valor' => $valor
            ];
        }

        // Preparar o resultado final
        $resultado = [
            'data_referencia' => $dataReferencia,
            'saldos_contas' => $saldosPorConta,
            'rateios_advogados' => $rateiosPorAdvogado,
            'saldo_total' => array_sum(array_column($saldosPorConta, 'saldo')),
            'rateio_total' => array_sum(array_column($rateiosPorAdvogado, 'valor'))
        ];

        // Verificar se é uma requisição AJAX
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($resultado);
        }

        // Preparar dados para a view
        $data = [
            'titulo' => 'Balanço Financeiro',
            'resultado' => $resultado
        ];

        // Verificar se é para impressão
        $print = $this->request->getGet('print');
        if ($print) {
            return view('financeiro/balancoImprimir', $data);
        }

        return view('financeiro/balanco', $data);
    }

    /**
     * Exibe o extrato da conta para um período específico com rateio
     * 
     * @param int $conta_id ID da conta
     * @param string $dataInicial Data inicial no formato yyyy-mm-dd (opcional)
     * @param string $dataFinal Data final no formato yyyy-mm-dd (opcional)
     * @return string|void Renderiza a view do extrato por período ou retorna JSON se solicitado
     */
    public function extrato()
    {
        $conta_id = $this->request->getGet('conta_id')??1;
        $dataInicial = $this->request->getGet('dataInicial')??null;
        $dataFinal = $this->request->getGet('dataFinal')??null;
        
        // Se as datas não forem fornecidas, usar o mês atual
        if ($dataInicial === null || $dataFinal === null) {
            $dataInicial = date('Y-m-01'); // Primeiro dia do mês atual
            $dataFinal = date('Y-m-t');    // Último dia do mês atual
        }

        // Obter os dados do extrato
        $extrato = $this->extratoService->getExtratoPorPeriodo($conta_id, $dataInicial, $dataFinal);

        // Verificar se é uma requisição AJAX
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($extrato);
        }

        // Preparar dados para a view
        $data = [
            'titulo' => 'Extrato por Período',
            'extrato' => $extrato,
            'conta_id' => $conta_id,
            'dataInicial' => $dataInicial,
            'dataFinal' => $dataFinal
        ];

        // Verificar se é para impressão
        $print = $this->request->getGet('print');
        if ($print) {
            return view('financeiro/extratoPeriodoImprimir', $data);
        }

        return view('financeiro/extratoPeriodo', $data);
    }


    public function balanco()
    {
        
        $dataReferencia = $this->request->getGet('dataReferencia')??null;
        // Se a data não for fornecida, usar data atual
        if ($dataReferencia === null) {
            $dataReferencia = date('Y-m-d');
        }

        // Buscar todas as contas disponíveis
        $contaModel = model('Financeiro/FinanceiroContasModel');
        $contas = $contaModel->where('deleted_at is null')->findAll();

        // Inicializar arrays para armazenar resultados
        $saldosPorConta = [];
        $rateiosPorAdvogado = [];

        // Para cada conta, calcular o saldo e o rateio até a data de referência
        foreach ($contas as $conta) {
            $conta_id = $conta['id_conta'];

            // Calcular extrato até a data de referência (do início do sistema até a data de referência)
            $extratoResultado = $this->extratoService->getExtratoPorPeriodo($conta_id, '2000-01-01', $dataReferencia);

            // Armazenar o saldo final da conta
            $saldosPorConta[$conta_id] = [
                'conta_nome' => $conta['conta'],
                'saldo' => $extratoResultado['saldo_final']
            ];

            // Processar os valores de rateio para cada advogado
            if (isset($extratoResultado['rateio_acumulado'])) {
                foreach ($extratoResultado['rateio_acumulado'] as $advogado_id => $dados) {
                    // Se o advogado_id não for "geral", adicionar ao rateio do advogado
                    if ($advogado_id !== 'geral') {
                        if (!isset($rateiosPorAdvogado[$advogado_id])) {
                            $rateiosPorAdvogado[$advogado_id] = 0;
                        }

                        $rateiosPorAdvogado[$advogado_id] += $dados['rateio_acumulado'];
                    }
                }
            }
        }

        // Buscar nomes dos advogados
        $advogadoModel = new ResposavelModel();
        foreach ($rateiosPorAdvogado as $advogado_id => &$valor) {
            $advogado = $advogadoModel->where('id',$advogado_id)->first();
            log_message('debug', 'Advogado ID: ' . $advogado_id . ', Nome: ' . $advogado['username']);
            $nome = $advogado ? $advogado['username'] : 'Advogado #' . $advogado_id;

            // Transformar o valor em um array com nome e valor
            $valor = [
                'nome' => $nome,
                'valor' => $valor
            ];
        }

        // Preparar o resultado final
        $resultado = [
            'data_referencia' => $dataReferencia,
            'saldos_contas' => $saldosPorConta,
            'rateios_advogados' => $rateiosPorAdvogado,
            'saldo_total' => array_sum(array_column($saldosPorConta, 'saldo')),
            'rateio_total' => array_sum(array_column($rateiosPorAdvogado, 'valor'))
        ];

        // Verificar se é uma requisição AJAX
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($resultado);
        }

        // Preparar dados para a view
        $data = [
            'titulo' => 'Balanço Financeiro',
            'resultado' => $resultado
        ];

        // Verificar se é para impressão
        $print = $this->request->getGet('print');
        if ($print) {
            return view('financeiro/balancoImprimir', $data);
        }

        return view('financeiro/balanco', $data);
    }
}
