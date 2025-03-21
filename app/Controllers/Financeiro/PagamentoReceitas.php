<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Traits\FormataValorTrait;
use Exception;

class PagamentoReceitas extends BaseController
{
    use FormataValorTrait;

    public function index()
    {
        $data = [
            'titulo' => 'Receitas Recebidas',
        ];
        $pagamentosModel = model('Financeiro/FinanceiroPagtoReceitasModel')
                                ->join('fin_receitas', 'fin_receitas.id_receita = fin_pgto_receitas.receita_id')
                                ->select('fin_pgto_receitas.*, fin_receitas.receita');
        $data['pagtoReceitas'] = $pagamentosModel->paginate(25);
        $data['pager'] = $pagamentosModel->pager;

        return $this->loadView('pagto_receitas/pagto_receitas', $data);
        
    }

    public function salvar()
    {
        $id = $this->request->getPost('id_pgto_receita') ?? null;
        $data = $this->request->getPost();
        
        // Formata o valor do pagamento
        $data['valor'] = $this->formatarValorParaBanco($this->request->getPost('valor'));
        
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
                model('Financeiro/FinanceiroPagtoReceitasModel')->insert($data);
                $id = model('Financeiro/FinanceiroPagtoReceitasModel')->getInsertID();
                return redirect()
                    ->to(base_url('financeiro/pagamentoReceitas/editar/'.$id))
                    ->with('success', 'Pagamento salvo com sucesso');
            } catch (Exception $e) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Erro ao salvar Pagamento: ' . $e->getMessage());
            }
        }

        try {
            model('Financeiro/FinanceiroPagtoReceitasModel')->update($id, $data);
            return redirect()
                ->to(base_url('financeiro/pagamentoReceitas/editar/'.$id))
                ->with('success', 'Dados do pagamento atualizados com sucesso');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar dados do Pagamento: ' . $e->getMessage());
        }
    }

    public function pagarReceita($receita_id){
        $data = [
            'titulo' => 'Pagar Receita',
        ];
        $receita = model('Financeiro/FinanceiroReceitasModel')->find($receita_id);
        $rateioEmReais = [];
        if(!empty($receita['rateio'])){
            if (!is_array($receita['rateio'])) {
                $receita['rateio'] = json_decode($receita['rateio'], true);
            }

            foreach ($receita['rateio'] as $item) {
                $valorEmReais = ($item['valor'] / 100) * $receita['valor'];
                $rateioEmReais[] = [
                    'id' => $item['id'],
                    'valor' => $valorEmReais,
                ];
            }
        }    
        $data['pagtoReceita']['rateio'] = $rateioEmReais;
        $data['pagtoReceita']['receita_id'] = $receita_id;
        $data['pagtoReceita']['valor'] = $receita['valor'];

        return $this->loadView('pagto_receitas/consultarPagtoReceita', $data);
    }

    public function editar($id)
    {
        $data = [
            'titulo' => 'Editar Dados do Pagamento',
        ];
        
        $pagamento = model('Financeiro/FinanceiroPagtoReceitasModel')->find($id);
        
        // Decodifica o rateio se existir
        if (!empty($pagamento['rateio'])) {
            $pagamento['rateio'] = json_decode($pagamento['rateio'], true);
        }
        
        $data['pagtoReceita'] = $pagamento;
        
        return $this->loadView('pagto_receitas/consultarPagtoReceita', $data);
    }

    public function novo($receita_id = null)
    {
        $data = [
            'titulo' => 'Novo Pagamento',
            'contas' => model('Financeiro/FinanceiroContasModel')->findAll(),
            'users' => model('UsersModel')->findAll(),
        ];

        if ($receita_id) {
            $data['receita'] = model('Financeiro/FinanceiroReceitasModel')->find($receita_id);
        }

        return $this->loadView('pagto_receitas/consultarPagtoReceita', $data);
    }

    public function excluir($id)
    {
        try {
            model('Financeiro/FinanceiroPagtoReceitasModel')->delete($id);
            return redirect()
                ->to(base_url('financeiro/pagamentoReceitas'))
                ->with('success', 'Pagamento excluído com sucesso');
        } catch (Exception $e) {
            return redirect()
                ->to(base_url('financeiro/pagamentoReceitas'))
                ->with('error', 'Erro ao excluir Pagamento: ' . $e->getMessage());
        }
    }
}