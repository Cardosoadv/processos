<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Exception;

class Clientes extends BaseController
{
    public function index()
    {
        $data = [
            'img'       => 'vazio.png',
            'titulo'    => 'Clientes',
        ];
        $s = $this->request->getGet('s');
        
        $clientesModel = model('ClientesModel');

        if($s !== null){
            $clientesModel
                            ->like('nome', $s);
            
            $data['clientes'] = $clientesModel->paginate(25);
            $data['pager'] = $clientesModel->pager;
                
            Session()->set(['msg'=> null]);
            return view('clientes/clientes', $data);                    
            }
        
        $data['clientes'] = $clientesModel->paginate(25);
        $data['pager'] = $clientesModel->pager;

        Session()->set(['msg'=> null]);
        return view('clientes/clientes', $data);

    }

    public function salvar(){

        $id = $this->request->getPost('id_cliente') ?? null;
        
        if(! is_numeric($id)){
            $data = $this->request->getPost();
            try{
            model('ClientesModel')->insert($data);
            return redirect()->to(base_url('clientes'));
            }
            catch(Exception $e){
                Session()->set(['msg'=> $e]);
                return redirect()->to(base_url('clientes'));
            }
        }
    }

    public function editarCliente($id){
        $data = [
            'img'       => 'vazio.png',
            'titulo'    => 'Editar Dados do Cliente',
        ];
        $data['cliente'] = model('ClientesModel')->find($id);
        Session()->set(['msg'=> null]);

        return view('clientes/consultarClientes', $data);
    }

    public function novo(){
        $data = [
            'img'       => 'vazio.png',
            'titulo'    => 'Novo Cliente',
        ];
        Session()->set(['msg'=> null]);
        return view('clientes/consultarClientes', $data);
    }
}