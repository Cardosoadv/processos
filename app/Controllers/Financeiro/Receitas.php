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

    public function index()
    {
        $data = [
            'titulo'    => 'Receitas',
        ];
        $s = $this->request->getGet('s');
        
        $receitasModel = model('Financeiro/FinanceiroReceitasModel');

        if($s !== null){
            $receitasModel  ->like('nome', $s);
            
            $data['receitas'] = $receitasModel->paginate(25);
            $data['pager'] = $receitasModel->pager;

            return view('receitas/receitas', $data);                    
            }
        
        $data['receitas'] = $receitasModel->paginate(25);
        $data['pager'] = $receitasModel->pager;

        return view('receitas/receitas', $data);

    }

    public function salvar(){

        $id = $this->request->getPost('id_receita') ?? null;
        $data = $this->request->getPost();
        $data['valor'] = $this->formatarValorParaBanco($this->request->getPost('valor'));

        if(! is_numeric($id)){

            try{
                model('Financeiro/FinanceiroReceitasModel')->insert($data);
            $id = model('Financeiro/FinanceiroReceitasModel')->getInsertID();
            return redirect()->to(base_url('financeiro/pagamentoReceitas/pagarReceita/'.$id))->with('success', 'Receita salva com sucesso');
            }
            catch(Exception $e){

                return redirect()   ->back()
                                    ->withInput()
                                    ->with('error', 'Erro ao salvar Receita: ' . $e->getMessage());
            }
        }

        try{
            model('Financeiro/FinanceiroReceitasModel')->update($id, $data);
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
        $data['receita'] = model('Financeiro/FinanceiroReceitasModel')->find($id);

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
            model('Financeiro/FinanceiroReceitasModel')->delete($id);
            return redirect()->to(base_url('financeiro/receitas'))->with('success', 'Receita excluída com sucesso');
        }
        catch(Exception $e){
            return redirect()->to(base_url('financeiro/receitas'))->with('error', 'Erro ao excluir Receita: ' . $e->getMessage());
        }
    }


}