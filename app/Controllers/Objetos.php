<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Exception;

class Objetos extends BaseController
{
    private $objetoModel;
    public function __construct()
    {
        $this->objetoModel = model('ProcessoObjetoModel');
    }
    
    
    public function index()
    {
        $data['titulo'] = 'Objetos';
        $data['objetos'] = $this->objetoModel->paginate(25);
        $data['pager'] = $this->objetoModel->pager;
        return $this->loadView('objetos/objetos', $data);
    }

    public function novo()
    {
        $data['titulo'] = 'Novo Objeto';
        return $this->loadView('objetos/consultarObjetos', $data);
    }

    public function editar($id){
        $data['titulo'] = 'Editar Objeto';
        $data['objeto'] = $this->objetoModel->find($id);
        return $this->loadView('objetos/consultarObjetos', $data);
    }

    public function salvar(){
        $id = $this->request->getPost('id_objeto');
        $data = $this->request->getPost();

        if ($id) {
            try{
            $this->objetoModel->update($id, $data);
            return redirect()->to(base_url('objetos'))->with('success', 'Objeto atualizado com sucesso!');
            }
            catch(Exception $e){
                return redirect()->back()->with('error', 'Erro ao atualizar o objeto: ' . $e->getMessage());
            }
        } else {
            $this->objetoModel->insert($data);
        }
    }

    public function excluir($id)
    {
        $this->objetoModel->delete($id);
        return redirect()->to(base_url('objetos'));
    }

}
