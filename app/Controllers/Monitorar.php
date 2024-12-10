<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ReceberIntimacoes;
use App\Models\ProcessosMonitoradosModel;

class Monitorar extends BaseController
{
    private array $processosMonitorados;
    
    public function index()
    {
        
        
    }
    
    public function MonitorarProcessos(){

        $processosMonitoradosModel = new ProcessosMonitoradosModel();
        $processosMonitorados = $processosMonitoradosModel->findAll();

        foreach ($processosMonitorados as $processo){
            $numeroProcesso = preg_replace('/[^0-9]/', '', $processo['numero_processo']);
            $params = [
                'numeroProcesso' => $numeroProcesso,
            ];
            $receberIntimacoes = new ReceberIntimacoes();
            $receberIntimacoes->getIntimacoes($params);
        }
    }

    public function incluirProcessos(){

        $usuario_id = user_id();
        foreach ($this->processosMonitorados as $processo){
            $data = [
                'numero_processo'   =>$processo,
                'usuario_id'        =>$usuario_id,
            ];
            $processosMonitoradosModel = new ProcessosMonitoradosModel();
            $processosMonitoradosModel->insert($data);
        }
    }
}
