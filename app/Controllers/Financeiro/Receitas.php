<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Traits\FormataValorTrait;
use App\Traits\ValidacoesTrait;
use Exception;

class Receitas extends BaseController
{
    use FormataValorTrait;
    use ValidacoesTrait;

    protected $receitasModel;

    public function __construct(){
        $this->receitasModel = model('Financeiro/FinanceiroReceitasModel');
    }

    public function index()
    {
        $data = [
            'titulo'    => 'Receitas',
        ];
        $s = $this->request->getGet('s');
        $receitas = $this->receitasModel;

    
        $data['receitas'] = $receitas->listarReceitasNaoRecebidas();
        
    
        return view('receitas/receitas', $data);                    
    }

    public function salvar(){

        $id = $this->request->getPost('id_receita') ?? null;
        $data = $this->request->getPost();
        $data['valor'] = $this->formatarValorParaBanco($this->request->getPost('valor'));
        $data['categoria'] = (int)$this->request->getPost('categoria');
        $data['cliente_id'] = (int)$this->request->getPost('cliente_id');
        log_message('info', 'Dados do formulário de Receitas: ' . json_encode($data));

        if(! is_numeric($id)){

            try{
                $this->receitasModel->insert($data);
                $id = $this->receitasModel->getInsertID();
            return redirect()->to(base_url('financeiro/pagamentoReceitas/pagarReceita/'.$id))->with('success', 'Receita salva com sucesso');
            }
            catch(Exception $e){

                return redirect()   ->back()
                                    ->withInput()
                                    ->with('error', 'Erro ao salvar Receita: ' . $e->getMessage());
            }
        }

        try{
            $this->receitasModel->update($id, $data);
            return redirect()->to(base_url('financeiro/receitas/editar/'.$id))->with('success', 'Dados da receita atualizado com sucesso');
            }
            catch(Exception $e){

                return redirect()   ->back()
                                    ->withInput()
                                    ->with('error', 'Erro ao atualizar dados da Receita: ' . $e->getMessage());
            }

    }

    public function editar($id){
        $data = [  
            'titulo'    => 'Editar Dados da Receita',
        ];
        $data['receita'] = $this->receitasModel->find($id);

        return view('receitas/consultarReceitas', $data);
    }

    public function novo(){
        $data = [

            'titulo'    => 'Novo Receita',
        ];
        return view('receitas/consultarReceitas', $data);
    }

    public function excluir($id){
        try{
            $this->receitasModel->delete($id);
            return redirect()->to(base_url('financeiro/receitas'))->with('success', 'Receita excluída com sucesso');
        }
        catch(Exception $e){
            return redirect()->to(base_url('financeiro/receitas'))->with('error', 'Erro ao excluir Receita: ' . $e->getMessage());
        }
    }
}