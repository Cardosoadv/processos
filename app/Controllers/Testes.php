<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Testes extends BaseController
{
    public function index()
    {
        $permission['processos']    = (auth()->user()->can('module.processos'));
        $permission['pessoas']      = (auth()->user()->can('module.pessoas'));
        $permission['tarefas']      = (auth()->user()->can('module.tarefas'));
        $permission['financeiro']   = (auth()->user()->can('module.financeiro'));
        $permission['intimacoes']   = (auth()->user()->can('module.intimacoes'));
        echo '<pre>';
        print_r($permission);
        echo '</pre>';
    }


    public function partes(){
        
    $partes = model('ProcessosPartesModel')
    ->select('nome')
    ->findAll();
        foreach ($partes as $parte){
            echo '<pre>';
            print_r($parte);
            $intimacoesDestinatarios = model('IntimacoesDestinatariosModel')
            ->select(['polo', 'comunicacao_id'])
            ->where('nome', $parte['nome'])
            ->get()->getResultArray();
            print_r($intimacoesDestinatarios);
            foreach( $intimacoesDestinatarios as $intimacoesDestinatario){
                $intimacoes = model('IntimacoesModel')
                ->select('numero_processo')
                ->where('id_intimacao', $intimacoesDestinatario['comunicacao_id'])
                ->get()->getResultArray();
                print_r($intimacoes);
                $processo = model('ProcessosModel')
                ->select('id_processo')
                ->where('numero_processo', $intimacoes[0]['numero_processo'])
                ->get()
                ->getRowArray();
                print_r($processo);
            }
                
            
        }
    }

}
