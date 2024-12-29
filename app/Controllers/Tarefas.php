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
        $tarefas['id'] = $this->request->getGet("Tarefa-id");
        $tarefas['status'] = $this->request->getGet("status-id");
        print_r($tarefas);
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
            echo "Sucesso!";
        }
        catch(\Exception $e){
            echo "Erro! ".$e->getMessage();
        }
    }



}
