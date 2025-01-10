<?php

namespace App\Controllers;

class Tarefas extends BaseController
{
    public function index()
    {
        $data = [
            'permission' => ['processos'=>true, 'intimacoes'=>true, 'movimentos'=>true],
            'img'       =>  'vazio.png',
            'titulo'    => 'Dashboard'
        ];
        $data['responsaveis'] = model('ResposavelModel')->getUsers();
        $data['tarefas'] = model('TarefasModel')->findAll();
        helper('criarcartao');
        $data['cartoes'] = criarcartao($data['tarefas']);
        Session()->set(['msg'=> null]);
        return view('kamban', $data);
    }

    public function nova(){
        $data = [
            'tarefa'            => $this->request->getPost('tarefa'),
            'descricao'         => $this->request->getPost('descricao'),
            'prazo'             => $this->request->getPost('prazo'),
            'status'            => $this->request->getPost('status'),
            'responsavel'       => $this->request->getPost('responsavel'),
            'prioridade'        => $this->request->getPost('prioridade'),
            'processo_id'       => $this->request->getPost('processo_id'),
        ];
        $tarefasModel = model('TarefasModel');
        try{
        $tarefasModel->insert($data);
            return redirect()->to(base_url('tarefas'));
        }
        catch(\Exception $e){
            echo "Erro! ".$e->getMessage();
        }
    }

    public function editarStatus(){
        
        $tarefas['id'] = $this->request->getGet("Tarefa-id");
        $tarefas['status'] = $this->request->getGet("status-id");
        log_message('debug', 'Tarefa ID: ' . $tarefas['id']);
        log_message('debug', 'Status ID: ' . $tarefas['status']);
        $tarefasModel = model('TarefasModel');
        try{
            $tarefasModel->update($tarefas['id'], ['status' => $tarefas['status']]);

            return $this->response->setJSON(['success' => true, 'message' => 'Status atualizado com sucesso!']);
            
        }
        catch(\Exception $e){
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function listarTarefas(){
        $tarefasModel = model('TarefasModel');
        $data = [
            'img'       =>  'vazio.png',
            'titulo'    => 'Tarefas',
        ];
        $data['tarefas'] = $tarefasModel->paginate(25);
        $data['pager'] = $tarefasModel->pager;
        Session()->set(['msg'=> null]);
        return view('tarefas', $data);

    }


}
