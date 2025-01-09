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

    public function salvar($id=null){

        if($id === null){
        $data = $this->request->getPost();
        echo '<pre>';
        print_r($data);
    
        // Para debug adicional, você pode adicionar:
        var_dump($this->request->getBody());
        var_dump($_POST);
        }
    }
}
