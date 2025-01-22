<?php

namespace App\Services;

class TarefasService
{
    private $tarefasModel;

    public function __construct()
    {
        $this->tarefasModel = model('TarefasModel');
    }

    public function listarTarefas($minhas = false, $emAndamento = false)
    {
        $query = $this->tarefasModel->orderBy('status', 'ASC');

        if ($minhas) {
            $query->where('responsavel', user_id());
        }

        if ($emAndamento) {
            $query->whereIn('status', [1, 2, 3]);
        }

        return $query->findAll();
    }

    public function buscarTarefa(int $id)
    {
        return $this->tarefasModel->where('id_tarefa', $id)->first();
    }

    public function salvarTarefa($idTarefa, array $dados)
    {
        if (is_numeric($idTarefa)) {
            $this->tarefasModel->update($idTarefa, $dados);
            return 'Tarefa atualizada com sucesso!';
        }

        $this->tarefasModel->insert($dados);
        return 'Tarefa adicionada com sucesso!';
    }

    public function atualizarStatus($tarefaId, $statusId)
    {
        log_message('debug', 'Tarefa ID: ' . $tarefaId);
        log_message('debug', 'Status ID: ' . $statusId);
        
        return $this->tarefasModel->update($tarefaId, ['status' => $statusId]);
    }

    public function listarTarefasPaginadas($porPagina = 25)
    {
        return [
            'tarefas' => $this->tarefasModel->paginate($porPagina),
            'pager'   => $this->tarefasModel->pager
        ];
    }

    public function atualizarTarefa($id, array $dados)
    {
        return $this->tarefasModel->update($id, $dados);
    }
}