<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ProcessoObjetoService;
use CodeIgniter\API\ResponseTrait;

class ProcessoObjeto extends BaseController
{
    use ResponseTrait;
    protected $service;

    public function __construct()
    {
        $this->service = new ProcessoObjetoService();
    }
    
    public function index()
    {
        $objetos = $this->service->listarObjetos();
        echo '<pre>';
        print_r($objetos);
        //return view('objetos/listar', ['objetos' => $objetos]);
    }

    public function salvar()
    {
        $dados = $this->request->getPost();

 
        $id = $this->service->salvarObjeto($dados);
        if ($id) {
            return redirect()->to(base_url('processos/consultarprocesso/').$dados['processo_id'])->with('success', 'Objeto salvo com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('erro', 'Erro ao salvar o objeto.');
        }
    }


}
