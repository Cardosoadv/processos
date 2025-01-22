<?php

namespace App\Services;

use App\Libraries\Auditoria\AuditoriaRecebimentoIntimacoes;

class AuditoriaService
{
    private $auditoriaRecebimentoIntimacoes;

    public function __construct()
    {
        $this->auditoriaRecebimentoIntimacoes = new AuditoriaRecebimentoIntimacoes();
    }

    public function registrarProcessamentoIntimacoes($dados)
    {
        return $this->auditoriaRecebimentoIntimacoes->registraProcessamentoIntimacoes($dados);
    }
}