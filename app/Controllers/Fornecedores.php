<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Traits\ValidacoesTrait;
use Exception;

class Fornecedores extends BaseController
{
    use ValidacoesTrait;

    protected $fornecedoresModel;

    public function __construct()
    {
        $this->fornecedoresModel = model('FornecedoresModel');
    }

    public function index()
    {
        $data = [
            'titulo'    => 'Fornecedores',
        ];
        $s = $this->request->getGet('s');
        

        if($s !== null){
            $this->fornecedoresModel  ->like('nome', $s);
            
            $data['fornecedores'] = $this->fornecedoresModel->paginate(25);
            $data['pager'] = $this->fornecedoresModel->pager;

            return view('fornecedores/fornecedores', $data);                    
            }
        
        $data['fornecedores'] = $this->fornecedoresModel->paginate(25);
        $data['pager'] = $this->fornecedoresModel->pager;

        return view('fornecedores/fornecedores', $data);

    }

    public function salvar(){

        $id = $this->request->getPost('id_fornecedor') ?? null;
        $data = $this->request->getPost();
        log_message('info', 'Dados do formulário: ' . json_encode($data));

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
                $this->fornecedoresModel->insert($data);
            $id = $this->fornecedoresModel->getInsertID();
            return redirect()->to(base_url('fornecedores/editar/'.$id))->with('success', 'Fornecedor salvo com sucesso');
            }
            catch(Exception $e){
                log_message('info', 'Erro ao inserir Fornecedor: ' . $e->getMessage());
                return redirect()   ->back()
                                    ->withInput()
                                    ->with('error', 'Erro ao salvar Fornecedor: ' . $e->getMessage());
            }
        }

        try{
            log_message('info', 'Atualizando dados do Fornecedor: ' . $id);
            $this->fornecedoresModel->update($id, $data);
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
        $data['fornecedor'] = $this->fornecedoresModel->find($id);

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
            $this->fornecedoresModel->delete($id);
            return redirect()->to(base_url('fornecedores'))->with('success', 'Fornecedor excluído com sucesso');
        }
        catch(Exception $e){
            return redirect()->to(base_url('fornecedores'))->with('error', 'Erro ao excluir Fornecedor: ' . $e->getMessage());
        }
    }
}