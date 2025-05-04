<?php

namespace App\Controllers;


use App\Controllers\BaseController;



class Mensagens extends BaseController
{

    private $mensagensModel;

    public function __construct()
    {
        $this->mensagensModel = model('MensagensModel');
    }


    public function index()
    {

        $data['titulo'] = 'Mensagens';
        $s = $this->request->getGet('s');

        $mensagens = $this->mensagensModel
            ->where('destinatario_id', user_id())
            ->orWhere('remetente_id', user_id());
            
        if ($s) {
            $mensagens
            ->groupStart()
                ->like('assunto', $s)
                ->orLike('conteudo', $s)
            ->groupEnd();
        }

        $mensagens
            ->orderBy("data_envio", "DESC");

        $data['mensagens'] = $mensagens->paginate(25);
        $data['pager'] = $this->mensagensModel->pager;

        return $this->loadView('mensagens/mensagens', $data);
    }



    public function salvar()
    {
        $remetenteId = user_id(); // Obtém o ID do usuário logado
        $destinatarioId = $this->request->getPost('destinatario_id');
        $conteudo = $this->request->getPost('conteudo');
        $assunto = $this->request->getPost('assunto');

        $data = [
            'remetente_id' => $remetenteId,
            'destinatario_id' => $destinatarioId,
            'conteudo' => $conteudo,
            'assunto' => $assunto,
            'data_envio' => date('Y-m-d H:i:s'),
        ];

        $this->mensagensModel->insert($data);

        return redirect()->to('/mensagens'); // Redireciona para a lista de mensagens
    }

    public function novo()
    {
        $data['titulo'] = 'Nova mensagem';
        return $this->loadView('mensagens/consultarMensagem', $data);
    }

    public function ler($mensagemId)
    {
        $data['titulo'] = 'Mensagem';
        $mensagem = $this->mensagensModel->find($mensagemId);
        

        if ($mensagem) {
            // Verifica se a mensagem já foi lida
            if ($mensagem['data_leitura'] === null) {
                // Atualiza a data de leitura
                $data['data_leitura'] = date('Y-m-d H:i:s');
                $dados = [
                    'data_leitura' => $data['data_leitura'],
                ];
                $this->mensagensModel->update($mensagemId, $dados);
            }

            $data['mensagem'] = $mensagem;

            // Exibe a mensagem
            return $this->loadView('mensagens/ler', $data);
        } else {
            // Mensagem não encontrada
            echo 'Mensagem não encontrada.';
        }
    }
}
