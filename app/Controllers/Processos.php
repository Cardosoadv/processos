<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProcessosPartesModel;

class Processos extends BaseController
{

    public function index(){
        $data = [
            'img'       =>  'vazio.png',
            'titulo'    => 'Processos',
        ];

        $table = new \CodeIgniter\View\Table();
        $processosModel = model('ProcessosModel');
        $processos = $processosModel
                ->findAll();
        //Seta os titulos da tabela
        $table->setHeading(['Numero Processo', 'Tribunal', 'Orgão', 'Ações']);
        //Define o template da tabela        
        $template = [
                        'table_open' => '<table class="table table-hover">',
                        'cell_start'  => '<td class="col-md-4">', // Define a largura da primeira coluna como 3/12 do container
                        'cell_alt_start' => '<td class="col-md-4">', // Deixa as outras colunas com largura automática
                    ];
        $table->setTemplate($template);   
        //Adiciona as linhas na tabela
        foreach ($processos as $processo) {
            $table->addRow([
                $processo['numeroprocessocommascara'],
                ['data' => $processo['siglaTribunal'], 'class' =>'col'], //Ajusta a largura desta coluna.
                $processo['nomeOrgao'],
                '<div class="btn-group">
                    <a href="' . base_url('processos/consultarprocesso/' . $processo['id_processo']) . '" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="' . base_url('processos/excluir/' . $processo['id_processo']) . '" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Excluir
                    </a>
                </div>'
            ]);
        }
        //Gera a tabela
        $data['table'] = $table->generate();
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
     * Este função apenas redireciona para o editar por id
     */
    public function editarPorNumerodeProcesso(string $numeroProcesso){

        $processosModel = model('ProcessosModel');
        $processo = $processosModel->where('numero_processo', $numeroProcesso)->get()->getRowArray();
        return redirect()->to(base_url('processos/consultarprocesso/' . $processo['id_processo']));
    }

    /**
     * Editar por $Id
     * Esta função que irá salvar os dados do processo no db
     * @param int $id
     */
    public function salvar(int $id=null){

        $partesProcessoModel = model('ProcessosPartesModel');
        $processosModel = model('ProcessosModel');

        if($id === null){
            //Metódo para salvar novo processo

            //Binding de campos do formulário
            $poloAtivo = $this->request->getPost('poloAtivo[]');
            $poloPassivo = $this->request->getPost('poloPassivo[]');
            $data = [
                'tipoDocumento'                 => $this->request->getPost('tipoDocumento'),
                'nomeOrgao'                     => $this->request->getPost('nomeOrgao'),
                'numeroprocessocommascara'      => $this->request->getPost('numeroprocessocommascara'),
                'dataDistribuicao'               => $this->request->getPost('dataDistribuicao'),
                'valorCausa'                     => $this->request->getPost('valorCausa'),
                'risco'                         => $this->request->getPost('risco'),
                'valorCondenacao'               => $this->request->getPost('valorCondenacao'),
                'comentario'                    => $this->request->getPost('comentario'),
                'resultado'                     => $this->request->getPost('resultado'),
            ];
            
            //Salvando Polo Ativo
            foreach ($poloAtivo as $ativo) {

                if($ativo === '' || $ativo === null){
                    continue;
                }
                $parte = [
                    'nome' => $ativo,
                    'polo' => 'A',
                ];
                $this->salvarPartes($parte, $id);
            }
            //Salvando Polo Passivo
            foreach ($poloPassivo as $passivo) {

                if($passivo === '' || $passivo === null){
                    continue;
                }
                $parte = [
                    'nome' => $passivo,
                    'polo' => 'P',
                ];
                $this->salvarPartes($parte, $id);
            }
            //Salvando Processo
            $processosModel->insert($data);
            //Recuperando ID do processo para redirecionar para a página de consulta
            $id = $processosModel->insertID();
            return redirect()->to(base_url('processos/consultarprocesso/' . $id));

        }else{
            //Método para editar processo

            //Binding de campos do formulário
            $poloAtivo = $this->request->getPost('poloAtivo[]');
            $poloPassivo = $this->request->getPost('poloPassivo[]');
            $data = [
                'id_processo'                   => $this->request->getPost('id_processo'),
                'tipoDocumento'                 => $this->request->getPost('tipoDocumento'),
                'siglaTribunal'                 => $this->request->getPost('siglaTribunal'),
                'nomeOrgao'                     => $this->request->getPost('nomeOrgao'),
                'numeroprocessocommascara'      => $this->request->getPost('numeroprocessocommascara'),
                'dataDistribuicao'               => $this->request->getPost('dataDistribuicao'),
                'valorCausa'                     => $this->request->getPost('valorCausa'),
                'risco'                         => $this->request->getPost('risco'),
                'valorCondenacao'               => $this->request->getPost('valorCondenacao'),
                'comentario'                    => $this->request->getPost('comentario'),
                'resultado'                     => $this->request->getPost('resultado'),
            ];
            //Limpa as partes do processo
            $partesProcessoModel->deletarParteDoProcesso($id);

            //Salva Polo Ativo do processo
            foreach ($poloAtivo as $ativo) {

                if($ativo === '' || $ativo === null){
                    continue;
                }
                $parte = [
                    'nome' => $ativo,
                    'polo' => 'A',
                ];
                $this->salvarPartes($parte, $id);
            }
            //Salva Polo Passivo do processo
            foreach ($poloPassivo as $passivo) {

                if($passivo === '' || $passivo === null){
                    continue;
                }
                $parte = [
                    'nome' => $passivo,
                    'polo' => 'P',
                ];
                $this->salvarPartes($parte, $id);
            }
            //Atualiza o processo e redireciona para a página de consulta
            $processosModel->update($id, $data);
            return redirect()->to(base_url('processos/consultarprocesso/' . $id));
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
        $data['anotacoes'] = model('ProcessosAnotacoesModel')->where('processo_id', $id)->get()->getResultArray();
        $data['movimentacoes'] = model('ProcessosMovimentosModel')->where('numero_processo', $numeroProcesso)->orderBy('dataHora', 'DESC')->limit(5)->get()->getResultArray();
        $data['intimacoes']= model('IntimacoesModel')->where('numero_processo', $numeroProcesso)->orderBy('data_disponibilizacao', 'DESC')->limit(5)->get()->getResultArray();
        $data['etiquetas'] = $processosModel->joinEtiquetasProcessos($id);
        return view('processos/consultarProcesso', $data);
    }

    /**
     * Inserir novo processo
     */
    public function novo(){
        $data = [
            'titulo'    => 'Novo Processo',
        ];
        $data['img'] = 'vazio.png';
        return view('processos/consultarProcesso', $data);
    }

    /**
     * Receber e salvar as anotoções do processo
     */
    public function adicionarAnotacao(){
        $processosAnotacoesModel = model('ProcessosAnotacoesModel');
        $data = $this->request->getPost();
        $processosAnotacoesModel->insert($data);
        return redirect()->to(base_url('processos/consultarprocesso/' . $data['processo_id']));
    }

}
