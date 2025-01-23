<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ProcessoService;


use App\Models\ProcessosPartesModel;

class Processos extends BaseController
{

    protected $processoService;

    public function __construct()
    {
        $this->processoService = new ProcessoService();
    }

    public function index()
    {
        $data = [
            'img'       => 'vazio.png',
            'titulo'    => 'Processos',
            'sortField' => $this->request->getGet('sort') ?? 'id_processo',
            'sortOrder' => $this->request->getGet('order') ?? 'asc',
            's'         => $this->request->getGet('s') ?? null,
            'encerrado' => $this->request->getGet('encerrado') ?? 0,
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

        Session()->set(['msg'=> null]);
        return view('processos/processos', $data);
    }
    
    public function processosDoCliente(?int $cliente_id)
    {
        $data = [
            'img'    => 'vazio.png',
            'titulo' => 'Processos',
            's'      => $this->request->getGet('s')
        ];

        $processos = $this->processoService->listarProcessosCliente($cliente_id, $data['s']);
        
        $data['pager'] = $processos['pager'];
        $data['processos'] = $processos['processos'];

        Session()->set(['msg'=> null]);
        return view('processos/processos', $data);
    }

    public function ProcessosMovimentados($dias)
    {
        $processos = $this->processoService->getProcessosMovimentados($dias);
        return $this->response->setJSON($processos);
    }

    public function editar(int $id = null)
    {
        return redirect()->to(base_url('processos/consultarprocesso/' . $id));
    }
    
    public function consultarProcesso(int $id = null)
    {
        $data = array_merge(
            ['titulo' => 'Consultar Processo', 'img' => 'vazio.png', 'selected' => $id],
            $this->processoService->getDetalhesProcesso($id)
        );

        Session()->set(['msg'=> null]);
        return view('processos/consultarProcesso', $data);
    }

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
            
            return redirect()   ->to(base_url('processos/consultarprocesso/' . $idProcesso))
                                ->with('success', 'Processo salvo com sucesso');
        } catch (\Exception $e) {
            return redirect()   ->back()
                                ->withInput()
                                ->with('error', 'Erro ao salvar processo: ' . $e->getMessage());
        }
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
                        'regex_match' => 'O número do processo está em formato inválido',
                    ]
            ],
        ];
        
        return $this->validate($rules);
    }


    private function prepararDadosProcesso()
    {
        return [
            'tipoDocumento'             => $this->request->getPost('tipoDocumento'),
            'titulo_processo'           => $this->request->getPost('titulo_processo'),
            'nomeOrgao'                 => $this->request->getPost('nomeOrgao'),
            'numeroprocessocommascara'  => $this->request->getPost('numeroprocessocommascara'),
            'numero_processo'           => preg_replace('/[^0-9]/', '', $this->request->getPost('numeroprocessocommascara')),
            'dataDistribuicao'          => $this->request->getPost('dataDistribuicao'),
            'valorCausa'                => $this->request->getPost('valorCausa'),
            'risco'                     => $this->request->getPost('risco'),
            'valorCondenacao'           => $this->request->getPost('valorCondenacao'),
            'comentario'                => $this->request->getPost('comentario'),
            'resultado'                 => $this->request->getPost('resultado'),
            'cliente_id'                => $this->request->getPost('cliente_id'),
            'dataRevisao'               => $this->request->getPost('dataRevisao'),
            'encerrado'                 => ($this->request->getPost('encerrado')) ? 1 : 0,
            'data_encerramento'         => $this->request->getPost('data_encerramento'),
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

    public function adicionarAnotacao()
    {
        $data = $this->request->getPost();
        $data['user_id'] = user_id();
        
        $this->processoService->salvarAnotacao($data);
        
        return redirect()->to(base_url('processos/consultarprocesso/' . $data['processo_id']));
    }

    public function removerEtiqueta()
    {
        $data = $this->request->getJSON();
        $success = $this->processoService->gerenciarEtiquetas($data->id_processo, $data->id_etiqueta, false);
        return $this->response->setJSON(['success' => $success]);
    }

    public function adicionarEtiqueta()
    {
        $data = $this->request->getJSON();
        $success = $this->processoService->gerenciarEtiquetas($data->id_processo, $data->id_etiqueta, true);
        return $this->response->setJSON(['success' => $success]);
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



    public function salvarMovimento(){
        $data = $this->request->getPost();
        $this->processoService->salvarMovimento($data);
        return redirect()->to(base_url('processos/editarpornumerodeprocesso/' . $data['numero_processo']));
    }

}
