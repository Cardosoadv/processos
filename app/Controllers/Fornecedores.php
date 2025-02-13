<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Exception;

class Fornecedores extends BaseController
{
    public function index()
    {
        $data = [
            'titulo'    => 'Fornecedores',
        ];
        $s = $this->request->getGet('s');
        
        $fornecedoresModel = model('Financeiro/FinanceiroFornecedoresModel');

        if($s !== null){
            $fornecedoresModel  ->like('nome', $s);
            
            $data['fornecedores'] = $fornecedoresModel->paginate(25);
            $data['pager'] = $fornecedoresModel->pager;

            return view('fornecedores/fornecedores', $data);                    
            }
        
        $data['fornecedores'] = $fornecedoresModel->paginate(25);
        $data['pager'] = $fornecedoresModel->pager;

        return view('fornecedores/fornecedores', $data);

    }

    public function salvar(){

        $id = $this->request->getPost('id_fornecedor') ?? null;
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
                model('Financeiro/FinanceiroFornecedoresModel')->insert($data);
            $id = model('Financeiro/FinanceiroFornecedoresModel')->getInsertID();
            return redirect()->to(base_url('fornecedores/editar/'.$id))->with('success', 'Fornecedor salvo com sucesso');
            }
            catch(Exception $e){

                return redirect()   ->back()
                                    ->withInput()
                                    ->with('error', 'Erro ao salvar Fornecedor: ' . $e->getMessage());
            }
        }

        try{
            model('Financeiro/FinanceiroFornecedoresModel')->update($id, $data);
            return redirect()->to(base_url('fornecedores/editar/'.$id))->with('success', 'Dados do fornecedor atualizado com sucesso');
            }
            catch(Exception $e){

                return redirect()   ->back()
                                    ->withInput()
                                    ->with('error', 'Erro ao atualizar dados do Fornecedor: ' . $e->getMessage());
            }

    }

    public function editar($id){
        $data = [  
            'titulo'    => 'Editar Dados do Fornecedor',
        ];
        $data['fornecedor'] = model('Financeiro/FinanceiroFornecedoresModel')->find($id);

        return view('fornecedores/consultarFornecedores', $data);
    }

    public function novo(){
        $data = [

            'titulo'    => 'Novo Fornecedor',
        ];
        return view('fornecedores/consultarFornecedores', $data);
    }

    public function excluir($id){
        try{
            model('Financeiro/FinanceiroFornecedoresModel')->delete($id);
            return redirect()->to(base_url('fornecedores'))->with('success', 'Fornecedor excluído com sucesso');
        }
        catch(Exception $e){
            return redirect()->to(base_url('fornecedores'))->with('error', 'Erro ao excluir Fornecedor: ' . $e->getMessage());
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