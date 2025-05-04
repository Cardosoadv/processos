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
        // Verifica se o usuário tem permissão para acessar o módulo de processos
        if(!((auth()->user()->can('module.financeiro'))
            )
        ){
            return redirect()->back()->withInput()->with('errors', 'Você não tem permissão para acessar Módulo Financeiro.');
        }
        
        $data = [
            'titulo'    => 'Despesas',
        ];
        $s = $this->request->getGet('s');
        $pagarDespesa = $this->request->getGet('pagarDespesa');

        
        $despesasModel = model('Financeiro/FinanceiroDespesasModel');
 
        if($s !== null){
            
            $filtros['despesa'] = $s;
            
            $data['despesas'] = $despesasModel->listarDespesasNaoPagas($filtros);
            

            return $this->loadView('despesas/despesas', $data);                    
            }
        
        $data['despesas'] = $despesasModel->listarDespesasNaoPagas();
        //$data['pager'] = $despesasModel->pager;

        return $this->loadView('despesas/despesas', $data);

    }

    public function salvar(){

// Verifica se o usuário tem permissão para acessar o módulo de processos
if(!((auth()->user()->can('module.financeiro'))
)
){
return redirect()->back()->withInput()->with('errors', 'Você não tem permissão para acessar Módulo Financeiro.');
}
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
        
        // Verifica se o usuário tem permissão para acessar o módulo de processos
        if(!((auth()->user()->can('module.financeiro'))
            )
        ){
            return redirect()->back()->withInput()->with('errors', 'Você não tem permissão para acessar Módulo Financeiro.');
        }
        $data = [  
            'titulo'    => 'Editar Dados do Despesa',
        ];
        $data['despesa'] = model('Financeiro/FinanceiroDespesasModel')->find($id);
        $pagarDespesa = $this->request->getGet('pagarDespesa');
        $data['pagarDespesa'] = $pagarDespesa;

        return $this->loadView('despesas/consultarDespesas', $data);
    }

    public function novo(){
        $data = [

            'titulo'    => 'Novo Despesa',
        ];
        return $this->loadView('despesas/consultarDespesas', $data);
    }

    public function excluir($id){
        // Verifica se o usuário tem permissão para acessar o módulo de processos
        if(!((auth()->user()->can('module.financeiro'))
            )
        ){
            return redirect()->back()->withInput()->with('errors', 'Você não tem permissão para acessar Módulo Financeiro.');
        }
        
        try{
            model('Financeiro/FinanceiroDespesasModel')->delete($id);
            return redirect()->to(base_url('financeiro/despesas'))->with('success', 'Despesa excluído com sucesso');
        }
        catch(Exception $e){
            return redirect()->to(base_url('financeiro/despesas'))->with('error', 'Erro ao excluir Despesa: ' . $e->getMessage());
        }
    }
}