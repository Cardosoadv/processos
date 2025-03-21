<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Models\Financeiro\FinanceiroCategoriasModel;

class Categorias extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new FinanceiroCategoriasModel();
    }

    public function index()
    {
        $s=$this->request->getGet('s');
        
        $categorias = $this->model->orderBy('categoria');
        
        $data['titulo'] = 'Categorias';

        if ($s) {
            $categorias->like('categoria', $s);
            $data['categorias'] = $categorias->paginate(25);
        } else {
            $data['categorias'] = $categorias->paginate(25);
        }

        $data['pager'] = $this->model->pager;
        return $this->loadView('categorias/categorias', $data);
    }

    public function novo()
    {
        $data['titulo'] = 'Nova categoria';
        return $this->loadView('categorias/consultarCategoria', $data);
    }

    public function editar($id = null)
    {
        $data['titulo'] = 'Editar categoria';
        $data['categoria'] = $this->model->find($id);
        if (empty($data['categoria'])) {
            return redirect()->to(site_url('categorias/categorias'))->with('errors', 'Categoria não encontrada!');
        }
        return view('categorias/consultarCategoria', $data);
    }

    public function salvar()
    {
        $data = $this->request->getPost();
        if ($this->model->save($data)) {
            return redirect()->to(site_url('financeiro/categorias'))->with('success', 'Categoria salva com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function excluir($id = null)
    {
        if ($this->model->delete($id)) {
            return redirect()->to(site_url('financeiro/categorias'))->with('success', 'Categoria excluída com sucesso!');
        } else {
            return redirect()->to(site_url('financeiro/categorias'))->with('error', 'Erro ao excluir a categoria.');
        }
    }
}