<?php

namespace App\Controllers;

use App\Models\ProcessosModel;

class Home extends BaseController
{
    
    
    public function index(): string
    {
        $processosModel = model('ProcessosModel');
        $clientesModel = model('ClientesModel');
        $tarefasModel = model('TarefasModel');

        $tarefasUsuario = $tarefasModel ->where('responsavel', user_id())
                                        ->whereNotIn('status', [4,5])
                                        ->get()->getResultArray();
        $qteTarefas = count($tarefasUsuario);
        $qteClientes = count($clientesModel->findAll());
        $processos = $processosModel->findAll();
        $qteProcessos = count($processos);

        $data = [
            'qteProcessos'      => $qteProcessos,
            'qteClientes'       => $qteClientes,
            'qteTarefas'        => $qteTarefas,
            'tarefasUsuario'    => $tarefasUsuario,
            'img'       =>  'vazio.png',
            'titulo'    => 'Dashboard'
        ];
        $data['responsaveis'] = model('ResposavelModel')->getUsers();
        Session()->set(['msg'=> null]);
        return view('dashboard', $data);
    }

    


}
