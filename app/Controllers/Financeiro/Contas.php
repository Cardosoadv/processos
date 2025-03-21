<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Models\Financeiro\FinanceiroContasModel;

class Contas extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new FinanceiroContasModel();
    }

    public function index()
    {
        $data['titulo'] = 'Contas';
        $data['contas'] = $this->model->paginate(25);
        $data['pager'] = $this->model->pager;
        return $this->loadView('contas/contas', $data);
    }

    public function novo()
    {
        $data['titulo'] = 'Nova conta';
        return $this->loadView('contas/consultarConta', $data);
    }

    public function editar($id = null)
    {
        $data['titulo'] = 'Editar conta';
        $data['conta'] = $this->model->find($id);
        if (empty($data['conta'])) {
            return redirect()->to(site_url('contas/contas'))->with('errors', 'Conta não encontrada!');
        }
        return $this->loadView('contas/consultarConta', $data);
    }

    public function salvar()
    {
        $data = $this->request->getPost();
        if ($this->model->save($data)) {
            return redirect()->to(site_url('financeiro/contas'))->with('success', 'Conta salva com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function excluir($id = null)
    {
        if ($this->model->delete($id)) {
            return redirect()->to(site_url('financeiro/contas'))->with('success', 'Conta excluída com sucesso!');
        } else {
            return redirect()->to(site_url('financeiro/contas'))->with('error', 'Erro ao excluir a conta.');
        }
    }
}