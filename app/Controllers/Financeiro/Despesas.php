<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Traits\FormataValorTrait;
use App\Traits\ValidacoesTrait;
use Exception;

class Despesas extends BaseController
{
    use FormataValorTrait;
    use ValidacoesTrait;

    public function index()
    {
        $data = [
            'titulo'    => 'Despesas',
        ];
        $s = $this->request->getGet('s');
        $pagarDespesa = $this->request->getGet('pagarDespesa');

        
        $despesasModel = model('Financeiro/FinanceiroDespesasModel');
 
        if($s !== null){
            
            $filtros['despesa'] = $s;
            
            $data['despesas'] = $despesasModel->listarDespesasNaoPagas($filtros);
            

            return view('despesas/despesas', $data);                    
            }
        
        $data['despesas'] = $despesasModel->listarDespesasNaoPagas();
        //$data['pager'] = $despesasModel->pager;

        return view('despesas/despesas', $data);

    }

    public function salvar(){


        $id = $this->request->getPost('id_despesa') ?? null;
        $data = $this->request->getPost();
        $data['valor'] = $this->formatarValorParaBanco($this->request->getPost('valor'));

        $pagarDespesa = $this->request->getPost('pagarDespesa');
        $data['pagarDespesa'] = $pagarDespesa;


        // Validação do CPF ou CNPJ
        $cpf_cnpj = $data['documento'] ?? null;
        if ($cpf_cnpj) {
            if (!$this->validarCpfCnpj($cpf_cnpj)) {
                return redirect()   ->back()
                                    ->withInput()
                                    ->with('error', 'CPF ou CNPJ inválido.');
            }
        }
 
        if(! is_numeric($id)){

            try{
                model('Financeiro/FinanceiroDespesasModel')->insert($data);
            $id = model('Financeiro/FinanceiroDespesasModel')->getInsertID();


            if($pagarDespesa == 1){

                return redirect()->to(base_url('financeiro/pagamentoDespesas/pagarDespesa/'.$id.'?pagarDespesa=1'))->with('success', 'Despesa salvo com sucesso');
            
            }else{

                return redirect()->to(base_url('financeiro/despesas/editar/'.$id))->with('success', 'Despesa salvo com sucesso');
            }
        }
            catch(Exception $e){

                return redirect()   ->back()
                                    ->withInput()
                                    ->with('error', 'Erro ao salvar Despesa: ' . $e->getMessage());
            }
        }

        try{
            model('Financeiro/FinanceiroDespesasModel')->update($id, $data);

            if($pagarDespesa == 1){

                return redirect()->to(base_url('financeiro/pagamentoDespesas/pagarDespesa/'.$id.'?pagarDespesa=1'))->with('success', 'Despesa salvo com sucesso');
            
            }else{

                return redirect()->to(base_url('financeiro/despesas/editar/'.$id))->with('success', 'Despesa salvo com sucesso');
            
            }
        }
            catch(Exception $e){

                return redirect()   ->back()
                                    ->withInput()
                                    ->with('error', 'Erro ao atualizar dados do Despesa: ' . $e->getMessage());
            }

    }

    public function editar($id){
        $data = [  
            'titulo'    => 'Editar Dados do Despesa',
        ];
        $data['despesa'] = model('Financeiro/FinanceiroDespesasModel')->find($id);
        $pagarDespesa = $this->request->getGet('pagarDespesa');
        $data['pagarDespesa'] = $pagarDespesa;

        return view('despesas/consultarDespesas', $data);
    }

    public function novo(){
        $data = [

            'titulo'    => 'Novo Despesa',
        ];
        return view('despesas/consultarDespesas', $data);
    }

    public function excluir($id){
        try{
            model('Financeiro/FinanceiroDespesasModel')->delete($id);
            return redirect()->to(base_url('financeiro/despesas'))->with('success', 'Despesa excluído com sucesso');
        }
        catch(Exception $e){
            return redirect()->to(base_url('financeiro/despesas'))->with('error', 'Erro ao excluir Despesa: ' . $e->getMessage());
        }
    }
}