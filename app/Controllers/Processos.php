<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ProcessoService;


use App\Models\ProcessosPartesModel;
use App\Traits\FormataValorTrait;



class Processos extends BaseController
{
    use FormataValorTrait;

    protected $processoService;

    public function __construct()
    {
        $this->processoService = new ProcessoService();
    }

    /**
     * Lista todos os processos
     * Com Filtro e pesquisa
     * @return string
     */
    public function index()
    {
        $data = [

            'titulo'    => 'Processos',
            'sortField' => $this->request->getGet('sort') ?? 'id_processo',
            'sortOrder' => $this->request->getGet('order') ?? 'asc',
            's'         => $this->request->getGet('s') ?? null,
            'encerrado' => $this->request->getGet('encerrado') ?? null,
            'etiqueta'  => $this->request->getGet('etiqueta') ?? null,
        ];

        $data['nextOrder'] = $data['sortOrder'] === 'asc' ? 'desc' : 'asc';

        $processos = $this->processoService->listarProcessos(
            $data['s'],
            $data['sortField'],
            $data['sortOrder'],
            $data['encerrado'],
            $data['etiqueta'],
        );

        $data['pager'] = $processos['pager'];
        $data['processos'] = $processos['processos'];
        return $this->loadView('processos/processos', $data);
    }

    /**
     * Lista os processos de um cliente
     * 
     * @param int $cliente_id
     * @return string
     */
    public function processosDoCliente(?int $cliente_id)
    {
        $data = [
            'titulo'    => 'Processos do Cliente',
            'sortField' => $this->request->getGet('sort') ?? 'id_processo',
            'sortOrder' => $this->request->getGet('order') ?? 'asc',
            's'         => $this->request->getGet('s') ?? null,
            'encerrado' => $this->request->getGet('encerrado') ?? null,
            'etiqueta'  => $this->request->getGet('etiqueta') ?? null,
        ];

        $data['nextOrder'] = $data['sortOrder'] === 'asc' ? 'desc' : 'asc';


        $processos = $this->processoService->listarProcessosCliente(
            $cliente_id,
            $data['s'],
            $data['sortField'],
            $data['sortOrder'],
            $data['encerrado'],
            $data['etiqueta'],
        );

        $data['pager'] = $processos['pager'];
        $data['processos'] = $processos['processos'];
        return $this->loadView('processos/processos', $data);
    }

    /**
     * Retorna os processos movimentados nos últimos dias
     * 
     * @param int $dias
     * @return json
     */
    public function processosMovimentados($dias)
    {
        $processos = $this->processoService->getProcessosMovimentados($dias);
        return $this->response->setJSON($processos);
    }

    ############################################################################################ 
    #                                                                                          #
    #                Metódos Relacionados à Edição dos Pocessos                                 #
    #                                                                                          #
    ############################################################################################

    /**
     * Exibe o Formulário de Novo Processo
     */
    public function novo()
    {
        $data = [
            'titulo'    => 'Novo Processo',
            'processo'  => ['cliente_id' => null],
        ];
        $data['img'] = 'vazio.png';
        $data['listaetiquetas'] = model('EtiquetasModel')->findAll();
        $data['etiquetas'] = [];
        return $this->loadView('processos/consultarProcesso', $data);
    }

    /**
     * Exibe todos os detalhes de um processo
     * Consulta um processo
     * 
     * @param int $id ID do processo
     * @return string
     */
    public function consultarProcesso(?int $id = null)
    {
        $data = array_merge(
            ['titulo' => 'Consultar Processo', 'img' => 'vazio.png', 'selected' => $id],
            $this->processoService->getDetalhesProcesso($id)
        );
        return $this->loadView('processos/consultarProcesso', $data);
    }

    /**
     * Salva um processo
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function salvar()
    {

    
        if (!$this->validarDadosProcesso()) {

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    
        try {
            // Converte o id para int ou null se estiver vazio
            $id = $this->request->getPost('id_processo');
            $id = !empty($id) ? (int)$id : null;

    
            $data = $this->prepararDadosProcesso();

    
            $idProcesso = $this->processoService->salvarProcesso($data, $id);
    
            $this->processarPartes('poloAtivo[]', 'A', $idProcesso);
            $this->processarPartes('poloPassivo[]', 'P', $idProcesso);
    
            return redirect()->to(base_url('processos/consultarprocesso/' . $idProcesso))
                ->with('success', 'Processo salvo com sucesso');

        } catch (\Exception $e) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao salvar processo: ' . $e->getMessage());
        }
    }

    /** 
     * Deleta um processo e seus registros relacionados
     * 
     * @param int $id ID do processo
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function excluir(int $id)
    {
        try {
            $this->processoService->deletarProcesso($id);

            return redirect()
                ->to(base_url('processos'))
                ->with('success', 'Processo deletado com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao deletar processo: ' . $e->getMessage());
        }
    }


    ############################################################################################ 
    #                                                                                          #
    #                Metódos Reacionados às Tabelas Auxiliares                                 #
    #                                                                                          #
    ############################################################################################




    /**
     * Processa as partes do processo
     * 
     * @param string $campo
     * @param string $tipo
     * @param int $processoId
     * @return void
     */
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
    public function salvarPartes(array $parte, int $idProcesso)
    {
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
     * Adiciona uma anotação a um processo
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function adicionarAnotacao()
    {
        $data = $this->request->getPost();
        $data['user_id'] = user_id();

        $this->processoService->salvarAnotacao($data);

        return redirect()->to(base_url('processos/consultarprocesso/' . $data['processo_id']));
    }


        /**
     * Adiciona uma etiqueta a um processo
     * 
     * @return json
     */
    public function adicionarEtiqueta()
    {
        $data = $this->request->getJSON();
        $success = $this->processoService->gerenciarEtiquetas($data->id_processo, $data->id_etiqueta, true);
        return $this->response->setJSON(['success' => $success]);
    }

    /**
     * Remove uma etiqueta a um processo
     * 
     * @return json
     */
    public function removerEtiqueta()
    {
        $data = $this->request->getJSON();
        $success = $this->processoService->gerenciarEtiquetas($data->id_processo, $data->id_etiqueta, false);
        return $this->response->setJSON(['success' => $success]);
    }

    /**
     * Sava etiquetas em lote
     */
    public function etiquetaEmLote(){
        $data = $this->request->getPost();
        if(!isset($data['etiqueta'])){
            return redirect()->back()->with('error', 'Nenhuma etiqueta selecionada');
        }
        if(!isset($data['processos'])){
            return redirect()->back()->with('error', 'Nenhum processo selecionado');
        }
        foreach ($data['processos'] as $processo) {
            $this->processoService->gerenciarEtiquetas($processo, $data['etiqueta'], true);
        }
        return redirect()->back()->with('success', 'Etiquetas adicionadas com sucesso');
    }

    /**
     * Salva um movimento no processo
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function salvarMovimento()
    {
        $data = $this->request->getPost();
        $this->processoService->salvarMovimento($data);
        return redirect()->to(base_url('processos/editarpornumerodeprocesso/' . $data['numero_processo']));
    }

    /**
     * Salva Vinculo entre processos
     */
    public function salvarVinculo()
    {
        $data = $this->request->getPost();
        try{
        $this->processoService->salvarVinculo($data);
        return redirect()   ->to(base_url('processos/editar/' . $data['id_processo_a']))
                            ->with('success', 'Vínculo salvo com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao salvar vinculo: ' . $e->getMessage());
        }
    }

    /**
     * Exclui um vinculo entre processos
     */
    public function excluirVinculo($id, $idProcesso){
        try{
        $this->processoService->excluirVinculo($id);
        return redirect()   ->to(base_url('processos/consultarprocesso/' . $idProcesso))
                            ->with('success', 'Vínculo excluido com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir vinculo: ' . $e->getMessage());
        }
    }


    ############################################################################################ 
    #                                                                                          #
    #        Metódos Auxiliares Relacionados à preparação dos dados do Pocessos                #
    #                                                                                          #
    ############################################################################################

    /**
     * Prepara os dados do processo para serem salvos
     * 
     * @return array
     */

    private function prepararDadosProcesso()
    {
        $valorCondenacao = $this->request->getPost('valorCondenacao')?:null;
        $valorCondenacao = $this->formatarValorParaBanco($valorCondenacao);
        $valorCausa                = $this->request->getPost('valorCausa')?:null;
        $valorCausa                = $this->formatarValorParaBanco($valorCausa);
        
        return [
            'tipoDocumento'             => $this->request->getPost('tipoDocumento')?:null,
            'titulo_processo'           => $this->request->getPost('titulo_processo'),
            'nomeOrgao'                 => $this->request->getPost('nomeOrgao')?:null,
            'numeroprocessocommascara'  => $this->request->getPost('numeroprocessocommascara'),
            'numero_processo'           => preg_replace('/[^0-9]/', '', $this->request->getPost('numeroprocessocommascara')),
            'dataDistribuicao'          => $this->request->getPost('dataDistribuicao')?:null,
            'valorCausa'                => $valorCausa,
            'risco'                     => $this->request->getPost('risco'),
            'valorCondenacao'           => $valorCondenacao,
            'comentario'                => $this->request->getPost('comentario')?:null,
            'resultado'                 => $this->request->getPost('resultado')?:null,
            'cliente_id'                => $this->request->getPost('cliente_id') ?:null,
            'dataRevisao'               => $this->request->getPost('dataRevisao')?:null,
            'encerrado'                 => ($this->request->getPost('encerrado')) ? 1 : 0,
            'data_encerramento'         => $this->request->getPost('data_encerramento')?:null,
        ];
    }

    /** 
     * Realiza a validacao dos dados vindos do formulário
     */
    private function validarDadosProcesso(): bool
    {
        $rules = [
            'titulo_processo' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'O Título do processo é obrigatório',
                ],
            ],
            'numeroprocessocommascara' => [
                'rules' => 'required|regex_match[/^\d{7}-\d{2}\.\d{4}\.\d\.\d{2}\.\d{4}$/]',
                'errors' => [
                    'required' => 'O número do processo é obrigatório',
                    'regex_match' => 'O número do processo está em formato inválido',
                ]
            ],
        ];

        return $this->validate($rules);
    }

    ############################################################################################ 
    #                                                                                          #
    #                            Metódos de Redirecionamento                                   #
    #                                                                                          #
    ############################################################################################

    /**
     * Apenas redireciona para a Consulta de Processo
     * 
     * @param int||null $id ID do processo
     * @return string
     */
    public function editar(?int $id)
    {
        return redirect()->to(base_url('processos/consultarprocesso/' . $id));
    }

    /**
     * Este função apenas redireciona para o editar por id
     */
    public function editarPorNumerodeProcesso(string $numeroProcesso)
    {

        $processosModel = model('ProcessosModel');
        $processo = $processosModel->where('numero_processo', $numeroProcesso)->get()->getRowArray();
        return redirect()->to(base_url('processos/consultarprocesso/' . $processo['id_processo']));
    }


    /**
     * Verifica se um processo existe no banco de dados
     * 
     * Recebe o número do processo formatado, remove caracteres não numéricos
     * e busca no banco de dados. Retorna um JSON indicando se o processo existe
     * e seus detalhes.
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface JSON com status da verificação
     */
    public function verificaProcessoExiste()
    {

        $numeroProcessoFormatado = $this->request->getJSON('numeroprocessocommascara');
        $numeroProcesso = preg_replace('/[^0-9]/', '', $numeroProcessoFormatado);
        $processosModel = model('ProcessosModel');
        $processo = $processosModel->where('numero_processo', $numeroProcesso)->get()->getRowArray();
        if ($processo) {
            return $this->response->setJSON(['success' => true, 'existe' => true, 'idProcesso' => $processo['id_processo'], $numeroProcesso, $numeroProcessoFormatado]);
        } else {
            return $this->response->setJSON(['success' => true, 'existe' => false, 'msg' => "Processo não encontrado. Cotinue o cadastramento.", $numeroProcesso, $numeroProcessoFormatado]);
        }
    }
}
