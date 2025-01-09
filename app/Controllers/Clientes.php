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

$data = $this->response->getPost();
echo '<pre>';
print_r($data);
}
