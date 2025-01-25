<?php

namespace App\Services;

use App\Models\ProcessoObjetoModel;

class ProcessoObjetoService
{
    protected $model;

    public function __construct()
    {
        $this->model = new ProcessoObjetoModel();
    }

    public function salvarObjeto(array $dados, $idProcesso): int
    {
        return $this->model->salvarObjeto($dados, $idProcesso);
    }

    public function obterObjeto(int $id): ?array
    {
        return $this->model->obterObjeto($id);
    }

        public function listarObjetos(): array
    {
        return $this->model->listarObjetos();
    } 
}