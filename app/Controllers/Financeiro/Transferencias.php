<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Models\Financeiro\FinanceiroTransferenciasModel;

class Transferencias extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new FinanceiroTransferenciasModel();
    }

    public function index()
    {
        $data['titulo'] = 'Transferencias';
        $data['transferencias'] = $this->model->paginate(25);
        $data['pager'] = $this->model->pager;
        return $this->loadView('transferencias/transferencias', $data);
    }

    public function novo()
    {
        $data['titulo'] = 'Nova transferencias';
        return $this->loadView('transferencias/consultarTransferencia', $data);
    }

    public function editar($id = null)
    {
        $data['titulo'] = 'Editar Transferencia';
        $data['transferencia'] = $this->model->find($id);
        if (empty($data['transferencia'])) {
            return redirect()->to(site_url('transferencias/transferencias'))->with('errors', 'Transferencia não encontrada!');
        }
        return $this->loadView('transferencias/consultarTransferencia', $data);
    }

    public function salvar()
    {
        $data = $this->request->getPost();
        if ($this->model->save($data)) {
            return redirect()->to(site_url('financeiro/transferencias'))->with('success', 'Transferencia salva com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function excluir($id = null)
    {
        if ($this->model->delete($id)) {
            return redirect()->to(site_url('financeiro/transferencias'))->with('success', 'Transferencia excluída com sucesso!');
        } else {
            return redirect()->to(site_url('financeiro/transferencias'))->with('error', 'Erro ao excluir a transferencia.');
        }
    }
}