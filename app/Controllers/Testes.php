<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\IntimacoesModel;
use App\Models\ProcessosModel;

class Testes extends BaseController
{
    public function index()
    {
        $intimascoesModel = new IntimacoesModel();
        $processoMovimentados = $intimascoesModel->getProcessoMovimentadoPeriodo('2024-01-01','2024-12-31');
        echo '<pre>';
        print_r($processoMovimentados);
        echo '</pre>';        
    }
}
