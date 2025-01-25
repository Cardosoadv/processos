<?php

namespace App\Services;

use App\Models\DecisaoJudicialModel;

class DecisaoJudicialService
{
    protected $model;

    public function __construct()
    {
        $this->model = new DecisaoJudicialModel();
    }

    public function salvarDecisao(array $dados): int
    {
        return $this->model->salvarDecisao($dados);
    }

    public function obterDecisao(int $id): ?array
    {
        return $this->model->obterDecisao($id);
    }

        public function listarDecisoes(): array
    {
        return $this->model->listarDecisoes();
    }
}