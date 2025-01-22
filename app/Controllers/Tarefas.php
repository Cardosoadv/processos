<?php

namespace App\Controllers;

class Tarefas extends BaseController
{
    private $tarefasService;

    public function __construct()
    {
        $this->tarefasService = new \App\Services\TarefasService();
    }

    public function index()
    {
        $data = [
            'img'    => 'vazio.png',
            'titulo' => 'Tarefas'
        ];
        
        $view = $this->request->getGet('view');
        $minhas = $this->request->getGet('minhas');
        $emAndamento = $this->request->getGet('emAndamento');

        $data['responsaveis'] = model('ResposavelModel')->getUsers();
        $data['tarefas'] = $this->tarefasService->listarTarefas($minhas, $emAndamento);

        if ($view == "Lista") {
            return view('tarefas/listaTarefas', $data);
        }

        helper('criarcartao');
        $data['cartoes'] = criarcartao($data['tarefas']);
        
        return view('tarefas/kamban', $data);
    }

    public function editar(?int $id)
    {
        $data = [
            'img'     => 'vazio.png',
            'titulo'  => 'Tarefas'
        ];

        $tarefa = $this->tarefasService->buscarTarefa($id);
        $data['tarefas'] = $tarefa;
        $data['selected'] = $tarefa['processo_id'];

        return view('tarefas/editarTarefa', $data);
    }

    public function nova()
    {
        $idTarefa = $this->request->getPost('id_tarefa');
        
        $tarefaData = [
            'tarefa'      => $this->request->getPost('tarefa'),
            'detalhes'    => $this->request->getPost('detalhes'),
            'prazo'       => $this->request->getPost('prazo'),
            'status'      => $this->request->getPost('status'),
            'responsavel' => $this->request->getPost('responsavel'),
            'prioridade'  => $this->request->getPost('prioridade'),
            'processo_id' => $this->request->getPost('processo_id'),
        ];

        try {
            $message = $this->tarefasService->salvarTarefa($idTarefa, $tarefaData);
            return redirect()->back()->withInput()->with('msg', $message);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('msg', "Erro! " . $e->getMessage());
        }
    }

    public function editarStatus()
    {
        $tarefaId = $this->request->getGet("Tarefa-id");
        $statusId = $this->request->getGet("status-id");

        try {
            $this->tarefasService->atualizarStatus($tarefaId, $statusId);
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Status atualizado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }

    public function listarTarefas()
    {
        $data = [
            'img'    => 'vazio.png',
            'titulo' => 'Tarefas',
        ];

        $resultado = $this->tarefasService->listarTarefasPaginadas(25);
        $data['tarefas'] = $resultado['tarefas'];
        $data['pager'] = $resultado['pager'];
        
        Session()->set(['msg'=> null]);
        
        return view('tarefas', $data);
    }

    public function atualizar()
    {
        $id = $this->request->getPost('id_tarefa');
        $tarefaData = [
            'tarefa'   => $this->request->getPost('tarefa'),
            'detalhes' => $this->request->getPost('detalhes'),
            'prazo'    => $this->request->getPost('prazo'),
        ];

        try {
            $this->tarefasService->atualizarTarefa($id, $tarefaData);
            return redirect()->back()->withInput()->with('msg', 'Tarefa atualizada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('msg', "Erro! " . $e->getMessage());
        }
    }
}