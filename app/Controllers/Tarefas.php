<?php

namespace App\Controllers;

class Tarefas extends BaseController
{
    public function index()
    {
        $data = [
            'img'       =>  'vazio.png',
            'titulo'    => 'Tarefas'
        ];
        
        $view = $this->request->getGet('view');
        $data['responsaveis'] = model('ResposavelModel')->getUsers();
        $data['tarefas'] = model('TarefasModel')->findAll();

        Session()->set(['msg'=> null]);

        if($view == "Lista"){
            return view('tarefas/listaTarefas', $data);
        }
        helper('criarcartao');
        $data['cartoes'] = criarcartao($data['tarefas']);

        return view('tarefas/kamban', $data);
    }

    public function editar (?int $id){
        $tarefasModel = model('TarefasModel');

        $data = [
            'img'       =>  'vazio.png',
            'titulo'    => 'Tarefas'
        ];

        $data['tarefas'] = $tarefasModel->where('id_tarefa', $id)->first();
        $data['selected'] = $data['tarefas']['processo_id'];

        return view('tarefas/editarTarefa', $data);
    }
    
    
    public function nova(){
        
        $idTarefa = $this->request->getPost('id_tarefa');
        
        $data = [
            'tarefa'            => $this->request->getPost('tarefa'),
            'detalhes'          => $this->request->getPost('detalhes'),
            'prazo'             => $this->request->getPost('prazo'),
            'status'            => $this->request->getPost('status'),
            'responsavel'       => $this->request->getPost('responsavel'),
            'prioridade'        => $this->request->getPost('prioridade'),
            'processo_id'       => $this->request->getPost('processo_id'),
        ];
        $tarefasModel = model('TarefasModel');

        if(is_numeric($idTarefa)){
            // Atualizar tarefa existente
            try{
            $tarefasModel->update($idTarefa,$data);
                return redirect()->back()->withInput()->with('msg', 'Tarefa atualizada com sucesso!');
            }
            catch(\Exception $e){
                return redirect()->back()->withInput()->with('msg', "Erro! ".$e->getMessage());
            }
        }else{
            // Adicionar nova tarefa
            try{
                $tarefasModel->insert($data);
                    return redirect()->back()->withInput()->with('msg', 'Tarefa adicionada com sucesso!');
            }
            catch(\Exception $e){
                return redirect()->back()->withInput()->with('msg', "Erro! ".$e->getMessage());
            }
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

    public function atualizar(){
        $id = $this->request->getPost('id_tarefa');
        $data = [
            'tarefa'            => $this->request->getPost('tarefa'),
            'detalhes'          => $this->request->getPost('detalhes'),
            'prazo'             => $this->request->getPost('prazo'),
        ];
        $tarefasModel = model('TarefasModel');
        try{
        $tarefasModel->update($id,$data);
            return redirect()->back()->withInput()->with('msg', 'Tarefa adicionada com sucesso!');
        }
        catch(\Exception $e){
            return redirect()->back()->withInput()->with('msg', "Erro! ".$e->getMessage());
        }
    }


}
