<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProcessosPartesModel;

class Processos extends BaseController
{

    public function index(){
        //TODO Incluir ordenação pelos cabeçahos da tabela
        $data = [
            'img'       =>  'vazio.png',
            'titulo'    => 'Processos',
        ];
        $s = $this->request->getGet('s');
        if($s === null){
            $processosModel = model('ProcessosModel');
            $processos = $processosModel
                                        ->joinProcessoCliente(25);
            $data['pager'] = $processos['pager'];
            $data['processos'] = $processos['processos'];
        }else{
            $processosModel = model('ProcessosModel');
            $processos = $processosModel
                                        ->groupStart() // Inicia o grupo de condições OR
                                            ->like('numero_processo', $s) // Pesquisa por numero do processo
                                            ->orLike('titulo_processo', $s) // OU Pelo Titulo dele
                                        ->groupEnd() // Finaliza o grupo de condições OR
                                        ->joinProcessoCliente(25)
                                        ;
            $data['pager'] = $processos['pager'];
            $data['processos'] = $processos['processos'];
        }

        Session()->set(['msg'=> null]);
        return view('processos/processos', $data);
    }
    
    /**
     * Retorna os processos do cliente
     * @param int $cliente_id
     * @return view com os processos do cliente
     */
    public function processosDoCliente(?int $cliente_id){
        
        $data = [
            'img'       =>  'vazio.png',
            'titulo'    => 'Processos',
        ];
        $s = $this->request->getGet('s');
        if($s === null){
            $processosModel = model('ProcessosModel');
            $processos = $processosModel
                                        ->where('cliente_id', $cliente_id)
                                        ->joinProcessoCliente(25);
            $data['pager'] = $processos['pager'];
            $data['processos'] = $processos['processos'];
        }else{
            $processosModel = model('ProcessosModel');
            $processos = $processosModel
                                        ->where('cliente_id', $cliente_id)
                                        ->groupStart() // Inicia o grupo de condições OR
                                            ->like('numero_processo', $s) // Pesquisa por numero do processo
                                            ->orLike('titulo_processo', $s) // OU Pelo Titulo dele
                                        ->groupEnd() // Finaliza o grupo de condições OR
                                        ->joinProcessoCliente(25)
                                        ;
            $data['pager'] = $processos['pager'];
            $data['processos'] = $processos['processos'];
        }

        Session()->set(['msg'=> null]);
        return view('processos/processos', $data);
    }

    /**
     * Retorna os processos movimentados em $dias
     * @param string $dias número de dias a serem consultados
     * @return json com os processos movimentados
     */
    public function ProcessosMovimentados($dias){
        $hoje = date('Y-m-d', time());
        $semanaPassada = date('Y-m-d', strtotime('-'.$dias.' days'));
        $processosMovimentadosModel = model('ProcessosMovimentosModel');
        $processosMovimentados = $processosMovimentadosModel->getProcessoMovimentadoPeriodo($semanaPassada, $hoje);
        return $this->response->setJSON($processosMovimentados);
    }

    /**
     * Exibe os dados individuais do processo
     * @param int $id
     */
    public function consultarProcesso(int $id=null){
        $processosModel = model('ProcessosModel');
        $partesProcessoModel = model('ProcessosPartesModel');
        $data = [
            'titulo'    => 'Consultar Processo',
        ];
        $data['img'] = 'vazio.png'; 
        $data['processo'] = $processosModel->where('id_processo', $id)->get()->getRowArray();
        $numeroProcesso = $data['processo']['numero_processo'];
        $data['poloAtivo'] = $partesProcessoModel->getParteProcesso($id, 'A');
        $data['poloPassivo'] = $partesProcessoModel->getParteProcesso($id, 'P');
        $data['anotacoes'] = model('ProcessosAnotacoesModel')->getAnotacoesPublicasOuDoUsuarioPorProcesso(user_id(), $id);
        $data['movimentacoes'] = model('ProcessosMovimentosModel')->where('numero_processo', $numeroProcesso)->orderBy('dataHora', 'DESC')->limit(5)->get()->getResultArray();
        $data['intimacoes']= model('IntimacoesModel')->where('numero_processo', $numeroProcesso)->orderBy('data_disponibilizacao', 'DESC')->limit(5)->get()->getResultArray();
        $data['etiquetas'] = $processosModel->joinEtiquetasProcessos($id);
        $data['tarefas'] = model('TarefasModel')->where('processo_id', $id)->get()->getResultArray();
        $data['selected'] = $id;
        Session()->set(['msg'=> null]);
        return view('processos/consultarProcesso', $data);
    }

    public function editar(int $id){

        return redirect()->to(base_url('processos/consultarprocesso/'.$id));
    }


    /**
     * Este função apenas redireciona para o editar por id
     */
    public function editarPorNumerodeProcesso(string $numeroProcesso){

        $processosModel = model('ProcessosModel');
        $processo = $processosModel->where('numero_processo', $numeroProcesso)->get()->getRowArray();
        return redirect()->to(base_url('processos/consultarprocesso/' . $processo['id_processo']));
    }

    /** 
     * Realiza a validacao dos dados vindos do formulário
     */
    private function validarDadosProcesso(): bool
    {
        $rules = [
            'titulo_processo' => 'required|min_length[3]',
            'numeroprocessocommascara' => [
                    'rules' => 'required|regex_match[/^\d{7}-\d{2}\.\d{4}\.\d\.\d{2}\.\d{4}$/]',
                    'errors' => [
                        'required' => 'O número do processo é obrigatório',
                        'regex_match' => 'O número do processo está em formato inválido'
                    ]
            ],
            'cliente_id' => 'required|numeric',
            // Adicionar outras regras
        ];
        
        return $this->validate($rules);
    }
    /**
     * Salva os dados do processo
     * Cria um novo processo ou atualiza um existente
     */
    public function salvar()
    {
        $id = $this->request->getPost('id_processo');
        
        // Validação dos dados
        if (!$this->validarDadosProcesso()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $partesProcessoModel = model('ProcessosPartesModel');
        $processosModel = model('ProcessosModel');
        
        try {
            // Inicia transaction
            $db = db_connect();
            $db->transStart();
            
            // Prepara dados comuns
            $data = $this->prepararDadosProcesso();
            
            if (!is_numeric($id)) {
                // Novo processo
                $processosModel->insert($data);
                $id = $processosModel->insertID();
            } else {
                // Atualização
                $partesProcessoModel->deletarParteDoProcesso(intval($id));
                $processosModel->update(intval($id), $data);
            }
            
            // Processa as partes
            $this->processarPartes('poloAtivo[]', 'A', $id);
            $this->processarPartes('poloPassivo[]', 'P', $id);
            
            // Finaliza transaction
            $db->transComplete();
            
            if ($db->transStatus() === FALSE) {
                throw new \Exception('Erro ao salvar processo');
            }
            
            return redirect()->to(base_url('processos/consultarprocesso/' . $id))
                            ->with('success', 'Processo salvo com sucesso');
                            
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erro ao salvar processo: ' . $e->getMessage());
        }
    }

    private function prepararDadosProcesso()
    {
        return [
            'tipoDocumento'             => $this->request->getPost('tipoDocumento'),
            'titulo_processo'           => $this->request->getPost('titulo_processo'),
            'nomeOrgao'                 => $this->request->getPost('nomeOrgao'),
            'numeroprocessocommascara'  => $this->request->getPost('numeroprocessocommascara'),
            'dataDistribuicao'          => $this->request->getPost('dataDistribuicao'),
            'valorCausa'                => $this->request->getPost('valorCausa'),
            'risco'                     => $this->request->getPost('risco'),
            'valorCondenacao'           => $this->request->getPost('valorCondenacao'),
            'comentario'                => $this->request->getPost('comentario'),
            'resultado'                 => $this->request->getPost('resultado'),
            'cliente_id'                => $this->request->getPost('cliente_id'),
        ];
    }
    
    private function processarPartes($campo, $tipo, $processoId)
    {
        $partes = $this->request->getPost($campo) ?? [];
        
        foreach ($partes as $parte) {
            if (empty($parte)) {
                continue;
            }
            
            $this->salvarPartes([
                'nome' => $parte,
                'polo' => $tipo
            ], $processoId);
        }
    }

    /**
     * Insere uma parte no processo
     * @param array $parte [strig nome, string polo]
     * @param int $idProcesso ID do processo
     * @return void
     */
    public function salvarPartes(array $parte, int $idProcesso){
        $processosPartesModel = new ProcessosPartesModel();

        // Confere se a parte já existe.
        $jaExisteParte = $processosPartesModel->where('nome', $parte['nome'])->first();

        if ($jaExisteParte) {
            // Se a Parte já existe, recurera sua ID.
            $idParteProcesso = $jaExisteParte['id_parte'];
        } else {
            // Se a Parte não existe, insira um novo record.
            $data = [
                'nome' => $parte['nome'],
            ];
            $processosPartesModel->insert($data);

            // Reupera o ID da parte inserida.
            $idParteProcesso = $processosPartesModel->insertID();
        }

        $parteProcesso = [
            'id_parte'    => $idParteProcesso,
            'id_processo' => $idProcesso,
            'polo'        => $parte['polo'],
        ];

        // Assuming salvarParteDoProcesso handles potential duplicates based on id_parte and id_processo
        $processosPartesModel->salvarParteDoProcesso($parteProcesso);
    }

    
    /**
     * Inserir novo processo
     */
    public function novo(){
        $data = [
            'titulo'    => 'Novo Processo',
            'processo'  => ['cliente_id'=>null],
        ];
        $data['img'] = 'vazio.png';
        $data['listaetiquetas'] = model('EtiquetasModel')->findAll();
        $data['etiquetas'] = [];
        return view('processos/consultarProcesso', $data);
    }

    /**
     * Receber e salvar as anotoções do processo
     */
    public function adicionarAnotacao(){
        $processosAnotacoesModel = model('ProcessosAnotacoesModel');
        $data = $this->request->getPost();
        $data['user_id'] = user_id();
        $processosAnotacoesModel->insert($data);
        return redirect()->to(base_url('processos/consultarprocesso/' . $data['processo_id']));
    }

    /**
     * Remover as etiquetas do processo por Ajax
     * @param json $data['id_processo', 'id_etiqueta']
     * @return json ['success' => true]
     */
    public function removerEtiqueta(){
        $data = $this->request->getJSON();
        $processosModel = model('ProcessosModel');
        $processosModel->removeEtiqueta($data->id_processo, $data->id_etiqueta);
        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Adicionar as etiquetas do processo por Ajax
     * @param json $data['id_processo', 'id_etiqueta']
     * @return json ['success' => true]
     */
    public function adicionarEtiqueta(){
        $data = $this->request->getJSON();
        $processosModel = model('ProcessosModel');
        $processosModel->addEtiqueta($data->id_processo, $data->id_etiqueta);
        return $this->response->setJSON(['success' => true]);
    }

}
