<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Processos extends BaseController
{

    public function index()
    {
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
                    <a href="' . base_url('processos/editar/' . $processo['id_processo']) . '" class="btn btn-primary">
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

    //TODO criar metodo de edição cumulado com novo processo
    /**
     * Editar por $Id
     * Esta função que irá salvar os dados do processo no db
     * @param int $id
     */
    public function editar(int $id){

        
    }

    public function consultarProcesso(int $id=null){
        $processosModel = model('ProcessosModel');
        $partesProcessoModel = model('ProcessosPartesModel');
        $data = [
            'titulo'    => 'Consultar Processo',
        ];
        $data['img'] = 'vazio.png';
        $data['processo'] = $processosModel->where('id_processo', $id)->get()->getRowArray();
        $data['poloAtivo'] = $partesProcessoModel->getParteProcesso($id, 'A');
        $data['poloPassivo'] = $partesProcessoModel->getParteProcesso($id, 'P');
        $data['anotacoes'] = model('ProcessosAnotacoesModel')->where('processo_id', $id)->get()->getResultArray();
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
