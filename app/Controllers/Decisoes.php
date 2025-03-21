<?php

namespace App\Controllers;

use App\Services\DecisaoJudicialService;
use CodeIgniter\API\ResponseTrait;

class Decisoes extends BaseController
{
    use ResponseTrait;
    protected $service;

    public function __construct()
    {
        $this->service = new DecisaoJudicialService();
    }

    public function index()
    {
                $decisoes = $this->service->listarDecisoes();
        return $this->loadView('decisoes/listar', ['decisoes' => $decisoes]);
    }

    public function criar()
    {
        return view('decisoes/criar');
    }

    public function salvar()
    {
        $dados = $this->request->getPost();

        // Processa os novos atributos
        $novosAtributos = $dados['novos_atributos'] ?? [];
        unset($dados['novos_atributos']); // Remove do array principal

        foreach ($novosAtributos as $atributo) {
            if (!empty($atributo['chave']) && !empty($atributo['valor'])) { // Verifica se chave e valor foram preenchidos
                $dados[$atributo['chave']] = $atributo['valor'];
            }
        }
 
        $id = $this->service->salvarDecisao($dados);

        if ($id) {
            return redirect()->to(base_url('/decisoes'))->with('mensagem', 'Decisão salva com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('erro', 'Erro ao salvar a decisão.');
        }
    }

    public function exibir($id)
    {
        $decisao = $this->service->obterDecisao($id);
        if ($decisao) {
            return $this->loadView('decisoes/exibir', ['decisao' => $decisao]);
        } else {
            return $this->failNotFound('Decisão não encontrada.');
        }
    }

}