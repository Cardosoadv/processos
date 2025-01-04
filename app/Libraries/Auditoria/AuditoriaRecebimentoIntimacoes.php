<?php

namespace App\Libraries\Auditoria;

use App\Models\AuditoriaIntimacaoModel;

class AuditoriaRecebimentoIntimacoes{

        //função para formatar a data. Ainda não foi testada.
        public function registraProcessamentoIntimacoes($data)
        {
            $auditoriaIntimacaoModel = new AuditoriaIntimacaoModel();
            $auditoriaIntimacaoModel->insert($data);
        }
}
