<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Traits\FormataValorTrait;
use Exception;

class PagamentoDespesas extends BaseController
{
    use FormataValorTrait;

    public function index()
    {
        $data = [
            'titulo' => 'Pagamentos de Despesas',
        ];
        $pagamentosModel = model('Financeiro/FinanceiroPagtoDespesasModel')
                                ->join('fin_despesas', 'fin_despesas.id_despesa = fin_pgto_despesas.despesa_id')
                                ->select('fin_pgto_despesas.*, fin_despesas.despesa');
        $data['pagtoDespesas'] = $pagamentosModel->orderBy('pagamento_despesa_dt')->paginate(25);
        $data['pager'] = $pagamentosModel->pager;

        return $this->loadView('pagto_despesas/pagto_despesas', $data);
        
    }

    public function salvar()
    {
        $id = $this->request->getPost('id_pagamento') ?? null;
        $data = $this->request->getPost();
        
        // Formata o valor do pagamento
        $data['valor'] = $this->formatarValorParaBanco($this->request->getPost('valor'));

        $pagarDespesa = $this->request->getPost('pagarDespesa');
        $data['pagarDespesa'] = $pagarDespesa;
        
        // Processa o rateio
        if (isset($data['rateio'])) {
            // Remove entradas vazias do rateio
            $data['rateio'] = array_filter($data['rateio'], function($item) {
                return !empty($item['id']) && !empty($item['valor']);
            });
            
            // Formata os valores do rateio
            foreach ($data['rateio'] as &$item) {
                $item['valor'] = $this->formatarValorParaBanco($item['valor']);
            }
            
            // Converte para JSON para armazenamento
            $data['rateio'] = json_encode($data['rateio']);
        }

        if (!is_numeric($id)) {
            try {
                model('Financeiro/FinanceiroPagtoDespesasModel')->insert($data);
                $id = model('Financeiro/FinanceiroPagtoDespesasModel')->getInsertID();

                if($pagarDespesa == 1){
                    return redirect()
                        ->to(base_url('financeiro/despesas/novo?pagarDespesa=1'))
                        ->with('success', 'Pagamento salvo com sucesso');
                } else {
                    return redirect()
                        ->to(base_url('financeiro/pagamentoDespesas/editar/'.$id))
                        ->with('success', 'Pagamento salvo com sucesso');
                }
            } catch (Exception $e) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Erro ao salvar Pagamento: ' . $e->getMessage());
            }
        }

        try {
            model('Financeiro/FinanceiroPagtoDespesasModel')->update($id, $data);
            if($pagarDespesa == 1){
                return redirect()
                    ->to(base_url('financeiro/despesas/novo?pagarDespesa=1'))
                    ->with('success', 'Pagamento salvo com sucesso');
            } else {
                return redirect()
                    ->to(base_url('financeiro/pagamentoDespesas/editar/'.$id))
                    ->with('success', 'Pagamento salvo com sucesso');
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar dados do Pagamento: ' . $e->getMessage());
        }
    }

    public function pagarDespesa($despesa_id){
        $data = [
            'titulo' => 'Pagar Despesa',
        ];
        $despesa = model('Financeiro/FinanceiroDespesasModel')->find($despesa_id);
        $pagarDespesa = $this->request->getGet('pagarDespesa');
        $data['pagarDespesa'] = $pagarDespesa;
        $rateioEmReais = [];
        if(!empty($despesa['rateio'])){
            if (!is_array($despesa['rateio'])) {
                $despesa['rateio'] = json_decode($despesa['rateio'], true);
            }

            foreach ($despesa['rateio'] as $item) {
                $valorEmReais = ($item['valor'] / 100) * $despesa['valor'];
                $rateioEmReais[] = [
                    'id' => $item['id'],
                    'valor' => $valorEmReais,
                ];
            }
        }    
        $data['pagtoDespesa']['rateio'] = $rateioEmReais;
        $data['pagtoDespesa']['despesa_id'] = $despesa_id;
        $data['pagtoDespesa']['valor'] = $despesa['valor'];

        return $this->loadView('pagto_despesas/consultarPagtoDespesa', $data);
    }

    public function editar($id)
    {
        $data = [
            'titulo' => 'Editar Dados do Pagamento',
        ];
        
        $pagamento = model('Financeiro/FinanceiroPagtoDespesasModel')->find($id);
        $pagarDespesa = $this->request->getGet('pagarDespesa');
        $data['pagarDespesa'] = $pagarDespesa;
        
        // Decodifica o rateio se existir
        if (!empty($pagamento['rateio'])) {
            $pagamento['rateio'] = json_decode($pagamento['rateio'], true);
        }
        
        $data['pagtoDespesa'] = $pagamento;
        
        return $this->loadView('pagto_despesas/consultarPagtoDespesa', $data);
    }

    public function novo($despesa_id = null)
    {
        $data = [
            'titulo' => 'Novo Pagamento',
            'contas' => model('Financeiro/FinanceiroContasModel')->findAll(),
            'users' => model('UsersModel')->findAll(),
        ];

        if ($despesa_id) {
            $data['despesa'] = model('Financeiro/FinanceiroDespesasModel')->find($despesa_id);
        }

        return $this->loadView('pagto_despesas/consultarPagtoDespesa', $data);
    }

    public function excluir($id)
    {
        try {
            model('Financeiro/FinanceiroPagtoDespesasModel')->delete($id);
            return redirect()
                ->to(base_url('financeiro/pagamentoDespesas'))
                ->with('success', 'Pagamento excluído com sucesso');
        } catch (Exception $e) {
            return redirect()
                ->to(base_url('financeiro/pagamentoDespesas'))
                ->with('error', 'Erro ao excluir Pagamento: ' . $e->getMessage());
        }
    }
}