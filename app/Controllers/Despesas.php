<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use Exception;

class Despesas extends BaseController
{
    public function index()
    {
        $data = [
            'titulo'    => 'Despesas',
        ];
        $s = $this->request->getGet('s');
        
        $despesasModel = model('Financeiro/FinanceiroDespesasModel');

        if($s !== null){
            $despesasModel  ->like('nome', $s);
            
            $data['despesas'] = $despesasModel->paginate(25);
            $data['pager'] = $despesasModel->pager;

            return view('despesas/despesas', $data);                    
            }
        
        $data['despesas'] = $despesasModel->paginate(25);
        $data['pager'] = $despesasModel->pager;

        return view('despesas/despesas', $data);

    }

    public function salvar(){

        $id = $this->request->getPost('id_despesa') ?? null;
        $data = $this->request->getPost();

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
            return redirect()->to(base_url('despesas/editar/'.$id))->with('success', 'Despesa salvo com sucesso');
            }
            catch(Exception $e){

                return redirect()   ->back()
                                    ->withInput()
                                    ->with('error', 'Erro ao salvar Despesa: ' . $e->getMessage());
            }
        }

        try{
            model('Financeiro/FinanceiroDespesasModel')->update($id, $data);
            return redirect()->to(base_url('despesas/editar/'.$id))->with('success', 'Dados do despesa atualizado com sucesso');
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
            return redirect()->to(base_url('despesas'))->with('success', 'Despesa excluído com sucesso');
        }
        catch(Exception $e){
            return redirect()->to(base_url('despesas'))->with('error', 'Erro ao excluir Despesa: ' . $e->getMessage());
        }
    }


    #------------------------------------------------------------------------------------------------
    #                            VALIDAÇÃO CPF OU CNPJ
    #------------------------------------------------------------------------------------------------

     /**
     * Função para validar CPF ou CNPJ
     */
    private function validarCpfCnpj($cpf_cnpj)
    {
        // Remove caracteres não numéricos
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $cpf_cnpj);

        // Verifica se é CPF
        if (strlen($cpf_cnpj) == 11) {
            return $this->validarCpf($cpf_cnpj);
        }

        // Verifica se é CNPJ
        if (strlen($cpf_cnpj) == 14) {
            return $this->validarCnpj($cpf_cnpj);
        }

        return false;
    }

    /**
     * Função para validar CPF
     */
    private function validarCpf($cpf)
    {
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação do CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * Função para validar CNPJ
     */
    private function validarCnpj($cnpj)
    {
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Validação do CNPJ
        for ($t = 12; $t < 14; $t++) {
            for ($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++) {
                $d += $cnpj[$c] * $p;
                $p = ($p == 2 || $p == 9) ? 9 : --$p;
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}