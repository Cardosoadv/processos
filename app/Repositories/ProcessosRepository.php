<?php

namespace App\Repositories;

use CodeIgniter\Database\BaseConnection;

class ProcessosRepository
{
    protected $db;
    protected $processosModel;
    protected $partesProcessoModel;
    protected $processosAnotacoesModel;
    protected $processosMovimentosModel;
    protected $intimacoesModel;
    protected $tarefasModel;
    protected $processosObjetoModel;
    protected $processosVinculadosModel;

    public function __construct()
    {
        $this->db                           = db_connect();
        $this->processosModel               = model('ProcessosModel');
        $this->partesProcessoModel          = model('ProcessosPartesModel');
        $this->processosAnotacoesModel      = model('ProcessosAnotacoesModel');
        $this->processosMovimentosModel     = model('ProcessosMovimentosModel');
        $this->intimacoesModel              = model('IntimacoesModel');
        $this->tarefasModel                 = model('TarefasModel');
        $this->processosObjetoModel         = model('ProcessoObjetoModel');
        $this->processosVinculadosModel    = model('ProcessosVinculadosModel');
    }