<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ReceberIntimacoes;
use App\Services\ReceberMovimentosDatajud;
use App\Models\ProcessosMonitoradosModel;

class Monitorar extends BaseController
{
    private array $processosMonitorados;
    
    public function index()
    {
        
        
    }
    
    public function ReceberMovimentos(){

        $receberMovimentosDatajud = new ReceberMovimentosDatajud();
        $processosMonitoradosModel = new ProcessosMonitoradosModel();

        $processosMonitorados = $processosMonitoradosModel  ->orderBy('ultima_checagem', 'ASC')
                                                            ->limit(5)
                                                            ->findAll();
        $table = [];
        foreach ($processosMonitorados as $processo){
            
            $tribunal = substr($processo['numero_processo'],13,3);
            $numeroProcesso = preg_replace('/[^0-9]/', '', $processo['numero_processo']);
            
            $data = $receberMovimentosDatajud->receberMovimentos($tribunal, $numeroProcesso);
            

            $data['ultima_checagem'] = date('Y-m-d H:i:s');
            
            $processosMonitoradosModel->update($processo['id_monitoramento'], $data);

            // Adiciona ao array $table
            $table[] = [
                'numero_processo' => $processo['numero_processo'],
                'data' => $data
            ];
        }
        $data['titulo'] = "Monitorar Movimentos";
        $data['table'] = $table;
        return $this->loadView('testes', $data);
        
    }

    public function MonitorarProcessos(){

        $processosMonitoradosModel = new ProcessosMonitoradosModel();
        $receberIntimacoes = new ReceberIntimacoes();

        $processosMonitorados = $processosMonitoradosModel  ->orderBy('ultima_checagem', 'ASC')
                                                            ->limit(50)
                                                            ->findAll();

        foreach ($processosMonitorados as $processo){
            $numeroProcesso = preg_replace('/[^0-9]/', '', $processo['numero_processo']);
            $params = [
                'numeroProcesso' => $numeroProcesso,
            ];
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
