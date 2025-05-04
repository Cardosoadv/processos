<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Traits\ValidacoesTrait;
use Exception;

class Clientes extends BaseController
{
    use ValidacoesTrait;

    protected $permission;



    public function verificaPermissao($permission = 'module.clientes')
    {
        return (auth()->user()->can($permission));
    }
    
    public function index()
    {
        if (! $this->verificaPermissao()) {

            return redirect()->to(base_url('home/permissao'))->with('errors', 'Você não tem permissão para acessar o módulo de Clientes!');
        }
        
        $data = [
            'titulo'    => 'Clientes',
        ];
        $s = $this->request->getGet('s');
        
        $clientesModel = model('ClientesModel')->orderBy('nome', 'ASC');

        if($s !== null){
            $clientesModel  ->like('nome', $s);
            
            $data['clientes'] = $clientesModel  ->orderBy('nome', 'ASC')
                                                ->paginate(25);
            $data['pager'] = $clientesModel->pager;

            return $this->loadView('clientes/clientes', $data);                    
            }
        
        $data['clientes'] = $clientesModel->paginate(25);
        $data['pager'] = $clientesModel->pager;

        return $this->loadView('clientes/clientes', $data);

    }

    public function salvar(){

        $id = $this->request->getPost('id_cliente') ?? null;
        $data = $this->request->getPost();

        // Validação do CPF ou CNPJ
        $cpf_cnpj = $data['documento'] ?? null;
        if ($cpf_cnpj) {
            if (!$this->validarCpfCnpj($cpf_cnpj)) {
                return redirect()   ->back()
                                    ->withInput()
                                    ->with('errors', 'CPF ou CNPJ inválido.');
            }
        }

        if(! is_numeric($id)){

            try{
            model('ClientesModel')->insert($data);
            $id = model('ClientesModel')->getInsertID();
            return redirect()->to(base_url('clientes/editar/'.$id))->with('success', 'Cliente salvo com sucesso');
            }
            catch(Exception $e){

                return redirect()   ->back()
                                    ->withInput()
                                    ->with('errors', 'Erro ao salvar Cliente: ' . $e->getMessage());
            }
        }

        try{
            model('ClientesModel')->update($id, $data);
            return redirect()->to(base_url('clientes/editar/'.$id))->with('success', 'Dados do cliente atualizado com sucesso');
            }
            catch(Exception $e){

                return redirect()   ->back()
                                    ->withInput()
                                    ->with('errors', 'Erro ao atualizar dados do Cliente: ' . $e->getMessage());
            }

    }

    public function editar($id){
        $data = [  
            'titulo'    => 'Editar Dados do Cliente',
        ];
        $data['cliente'] = model('ClientesModel')->find($id);

        return $this->loadView('clientes/consultarClientes', $data);
    }

    public function novo(){
        $data = [

            'titulo'    => 'Novo Cliente',
        ];
        return $this->loadView('clientes/consultarClientes', $data);
    }

    public function excluir($id){
        try{
            model('ClientesModel')->delete($id);
            return redirect()->to(base_url('clientes'))->with('success', 'Cliente excluído com sucesso');
        }
        catch(Exception $e){
            return redirect()->to(base_url('clientes'))->with('errors', 'Erro ao excluir Cliente: ' . $e->getMessage());
        }
    }
}