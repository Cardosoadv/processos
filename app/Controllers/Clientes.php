<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Clientes extends BaseController
{
    public function index()
    {
        $data = [
            'img'       =>  'vazio.png',
            'titulo'    => 'Processos',
        ];

        return view('clientes/consultarClientes', $data);

    }

    public function salvar(){

        $id = $this->request->getPost('id_cliente') ?? null;
        
        if(! is_numeric($id)){
        $data = $this->request->getPost();
        $clientesModel = model('ClientesModel')->insert($data);
 
        echo '<pre>';
        print_r($data);
    
        // Para debug adicional, você pode adicionar:
        var_dump($this->request->getBody());
        var_dump($_POST);
        }
    }
}
