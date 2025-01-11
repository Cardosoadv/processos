<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ReceberIntimacoes;
use App\Libraries\ReceberMovimentosDatajudJs;
use App\Models\ProcessosMonitoradosModel;

class MonitorarJs extends BaseController
{
    private array $processosMonitorados;
    
    public function index()
    {
        
        
    }
    
    public function ReceberMovimentos(){

        $receberMovimentosDatajud = new ReceberMovimentosDatajudJs();
        $processosMonitoradosModel = new ProcessosMonitoradosModel();
        $hoje = $hoje = date("Y-m-d");


        $processosMonitorados = $processosMonitoradosModel->orderBy('ultima_checagem', 'ASC')->limit(2)->findAll();

            $tribunalAtual = substr($processosMonitorados[0]['numero_processo'],16,4);
            $numeroProcessoAtual = preg_replace('/[^0-9]/', '', $processosMonitorados[0]['numero_processo']);
            $urlAtual = $receberMovimentosDatajud->definirUrl($tribunalAtual, $numeroProcessoAtual);

            $tribunalProximo = substr($processosMonitorados[1]['numero_processo'],16,4);
            $numeroProcessoProximo = preg_replace('/[^0-9]/', '', $processosMonitorados[1]['numero_processo']);
            $urlProximo = $receberMovimentosDatajud->definirUrl($tribunalProximo, $numeroProcessoProximo);
    
            if($processosMonitorados[0]['ultima_checagem']==$hoje){
                $urlProximo = null;
            }

            $data = [
                'urlAtual'      => $urlAtual,
                'numeroProcesso'=> $numeroProcessoAtual, 
                'urlProximo'    => $urlProximo,
                'apiKey'        => getenv('API_KEY')
            ];

            return view('monitorarjs', $data);
        }
/*
            $data = $receberMovimentosDatajud->receberMovimentos($tribunal, $numeroProcesso);
            $data['ultima_checagem'] = date('Y-m-d H:i:s');
            $processosMonitoradosModel->update($processo['id_monitoramento'], $data);
        }
        Echo "Movimentos Recebidos";
    }*/

    public function MonitorarProcessos(){

        $processosMonitoradosModel = new ProcessosMonitoradosModel();
        $receberIntimacoes = new ReceberIntimacoes();

        $processosMonitorados = $processosMonitoradosModel->findAll();

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
