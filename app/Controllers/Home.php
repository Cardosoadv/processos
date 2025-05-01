<?php

namespace App\Controllers;

class Home extends BaseController
{
    
    
    public function index(): string
    {
        $data = [
            'titulo' => 'Dashboard',
        ];
        
        $processosModel = model('ProcessosModel');
        $clientesModel = model('ClientesModel');
        $tarefasModel = model('TarefasModel');


        if(auth()->user()->can('exclusive.construtiva')){
            $processos = $processosModel->where('encerrado', 0)
                                        ->where('cliente_id', 2)
                                        ->get()->getResultArray();
            $data['qteProcessos'] = count($processos);
            
                                        return $this->loadView('dashboard', $data);
        }

        $tarefasUsuario = $tarefasModel ->where('responsavel', user_id())
                                        ->whereNotIn('status', [4,5])
                                        ->get()->getResultArray();
        $qteTarefas = count($tarefasUsuario);
        $qteClientes = count($clientesModel->findAll());
        $processos = $processosModel->where('encerrado', 0)->get()->getResultArray();
        $qteProcessos = count($processos);

        $data = [
            'qteProcessos'      => $qteProcessos,
            'qteClientes'       => $qteClientes,
            'qteTarefas'        => $qteTarefas,
            'tarefasUsuario'    => $tarefasUsuario,
        ];
        $data['responsaveis'] = model('ResposavelModel')->getUsers();
        return $this->loadView('dashboard', $data);
    }

    


}
