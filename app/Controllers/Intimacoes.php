<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Auditoria\AuditoriaRecebimentoIntimacoes;
use App\Libraries\ConverterData;
use App\Libraries\ReceberIntimacoes;
use App\Models\IntimacoesAdvogadosModel;
use App\Models\IntimacoesDestinatariosModel;
use App\Models\IntimacoesModel;
use App\Models\ProcessosModel;

class Intimacoes extends BaseController
{
    public function index()
    {

        $oab = "61061";
        $ufOab = "MG";

        $params = [
            'numeroOab' => $oab,
            'ufOab' => $ufOab
        ];

        $receberIntimacoes = new ReceberIntimacoes();
        $receberIntimacoes->getIntimacoes($params);
    }

    public function processo($numeroProcesso)
    {
        $params = [
            'numeroProcesso' => $numeroProcesso
        ];

        $receberIntimacoes = new ReceberIntimacoes();
        $receberIntimacoes->getIntimacoes($params);
    }

    /**
     * Efetua o tratamento das intimações organizando os dados e salvandos nas tabelas corretas
     */
    public function parseIntimacao(array $data, $filename) {

        $intimacoesModel = new IntimacoesModel();
        $usuario_id = user_id();
        $nomeArquivo = $filename;
        $statusRecebimentoIntimacao = $data['message'];
        $numeroIntimacoesRecebidas = $data['count'];
        $numeroIntimacoesRepetidas = 0;
        $numeroIntimacoesProcessadas = 0;

        if ($numeroIntimacoesRecebidas === 0) {
            return;
        }

        //Percorre os itens da intimação
        foreach ($data['items'] as $items) {

            //checa se a intimação já consta no db
            if ($intimacoesModel->intimacaoJaExiste($items['id']) === false) {

                //Verifica se existe e salva ou atualiza o processo no db
                $idProcesso = $this->salvarProcessos($items);

                //Salva ou atualiza a intimação no db
                $this->salvarIntimacao($items);

                //Percorre a lista de destinários salvando cada uma no db
                foreach ($items['destinatarios'] as $itemsDestinatario) {
                    //Salva ou atualiza o destinatário no db
                    $this->salvarDestinatarios($itemsDestinatario);
                    //Salva as partes do processo!
                    $processosController = new Processos();
                    $processosController->salvarPartes($itemsDestinatario, $idProcesso);
                }
                //Percorre a lista de advogados salvando cada uma no db
                foreach ($items['destinatarioadvogados'] as $itemsAdvogados) {
                    $this->salvarAdvogados($itemsAdvogados);
                }
                $numeroIntimacoesProcessadas++;
            } else {
                $numeroIntimacoesRepetidas++;
            }
        }
        //Binding de dados para auditoria    
        $data = [
            'status_recebimento_intimacao' => $statusRecebimentoIntimacao,
            'numero_intimacoes_recebidas' => $numeroIntimacoesRecebidas,
            'numero_intimacoes_repetidas' => $numeroIntimacoesRepetidas,
            'numero_intimacoes_processadas' => $numeroIntimacoesProcessadas,
            'nomeArquivo' => $nomeArquivo,
            'usuario_id' => $usuario_id,
        ];
        $auditoriaRecebimentoIntimacoes = new AuditoriaRecebimentoIntimacoes();
        $auditoriaRecebimentoIntimacoes->registraProcessamentoIntimacoes($data);
        $data['titulo'] = 'Intimações';
        return view('dashboard', $data);
    }

    /**
     * Salva os processos no banco de dados.
     */
    private function salvarProcessos($processos){
        $processosModel = new ProcessosModel();

        // Verifica se já existe um processo com o mesmo número.
        $jaExisteProcesso = $processosModel->where('numero_processo', $processos['numero_processo'])->first();

        if ($jaExisteProcesso) {
            // Processo já existe, retorna o ID existente.
            return $jaExisteProcesso['id_processo'];
        }

        $data = [
            'siglaTribunal'            => $processos['siglaTribunal'],
            'nomeOrgao'                => $processos['nomeOrgao'],
            'numero_processo'          => $processos['numero_processo'],
            'link'                     => $processos['link'],
            'tipoDocumento'            => $processos['tipoDocumento'],
            'codigoClasse'             => $processos['codigoClasse'],
            'ativo'                    => $processos['ativo'],
            'status'                   => $processos['status'] ?? 'P',
            'risco'                    => $processos['risco'] ?? 'Possível',
            'numeroprocessocommascara' => $processos['numeroprocessocommascara'],
        ];

        // Usa o método insert() para inserir os dados.
        $processosModel->insert($data);

        // Retorna o ID do processo inserido.
        return $processosModel->insertID();
    }

    /**
     * Salva as intimações no banco de dados.
     * @param array $intimacao
     * @return void
     */
    private function salvarIntimacao($intimacao){
        $intimacoesModel = new IntimacoesModel();
        $converterData = new ConverterData();

        //Binding de dados para salvar a intimação
        $data = [
            'id_intimacao'                      => $intimacao['id'],
            'data_disponibilizacao'             => $converterData->dataParaBancoDados($intimacao['data_disponibilizacao']),
            'tipoComunicacao'                   => $intimacao['tipoComunicacao'],
            'texto'                             => $intimacao['texto'],
            'numero_processo'                   => $intimacao['numero_processo'],
            'meio'                              => $intimacao['meio'],
            'link'                              => $intimacao['link'],
            'numeroComunicacao'                 => $intimacao['numeroComunicacao'],
            'hash'                              => $intimacao['hash'],
            'motivo_cancelamento'               => $intimacao['motivo_cancelamento'] ?? null,
            'data_cancelamento'                 => $intimacao['data_cancelamento'] ?? null,
            'datadisponibilizacao'              => $converterData->dataParaBancoDados($intimacao['datadisponibilizacao']),
            'dataenvio'                         => $converterData->dataParaBancoDados($intimacao['dataenvio']),
            'meiocompleto'                      => $intimacao['meiocompleto'],
        ];
        $intimacoesModel->insert($data);
    }

    /**
     * Salva os destinatários no banco de dados.
     * @param array $destinatario
     * @return void
     */
    private function salvarDestinatarios($destinatario){

        $intimacoesDestinatariosModel = new IntimacoesDestinatariosModel();
        
        //Binding de dados para salvar o destinatário
        $data = [
            'nome'               => $destinatario['nome'],
            'polo'               => $destinatario['polo'],
            'comunicacao_id'     => $destinatario['comunicacao_id'],
        ];
        $intimacoesDestinatariosModel->save($data);
    }

    /**
     * Salva os advogados no banco de dados.
     * @param array $advogado
     * @return void
     */
    private function salvarAdvogados($advogado){

        $intimacoesAdvogadosModel = new IntimacoesAdvogadosModel();

        //Binding de dados para salvar o advogado
        $data = [
            'id'                => $advogado['id'],
            'comunicacao_id'    => $advogado['comunicacao_id'],
            'advogado_id'       => $advogado['advogado']['id'],
            'advogado_nome'     => $advogado['advogado']['nome'],
            'advogado_oab'      => $advogado['advogado']['numero_oab'],
            'advogado_oab_uf'   => $advogado['advogado']['uf_oab'],
            'created_at'        => $advogado['created_at'],
            'updated_at'        => $advogado['updated_at'],
        ];
        $intimacoesAdvogadosModel->save($data);
    }

        /**
     * Retorna as intimações em $dias
     * @param string $dias número de dias a serem consultados
     * @return json com as intimções
     */
    public function intimacoesPorPeriodo($dias){
        $hoje = date('Y-m-d', time());
        $semanaPassada = date('Y-m-d', strtotime('-'.$dias.' days'));
        $intimacoesModel = model('IntimacoesModel');
        $intimacoes = $intimacoesModel->getIntimacoesdoPeriodo($semanaPassada, $hoje);
        return $this->response->setJSON($intimacoes);
    }

    public function parseIntimacaoJs($data, $filename) {

        $intimacoesModel = new IntimacoesModel();
        $usuario_id = user_id();
        $nomeArquivo = $filename;
        $statusRecebimentoIntimacao = $data->message;
        $numeroIntimacoesRecebidas = $data->count;
        $numeroIntimacoesRepetidas = 0;
        $numeroIntimacoesProcessadas = 0;

        if ($numeroIntimacoesRecebidas === 0) {
            return;
        }

        //Percorre os itens da intimação
        foreach ($data->items as $items) {

            //checa se a intimação já consta no db
            if ($intimacoesModel->intimacaoJaExiste($items->id) === false) {

                //Verifica se existe e salva ou atualiza o processo no db
                $idProcesso = $this->salvarProcessos(array($items));

                //Salva ou atualiza a intimação no db
                $this->salvarIntimacao(array($items));

                //Percorre a lista de destinários salvando cada uma no db
                foreach ($items->destinatarios as $itemsDestinatario) {
                    //Salva ou atualiza o destinatário no db
                    $this->salvarDestinatarios(array($itemsDestinatario));
                    //Salva as partes do processo!
                    $processosController = new Processos();
                    $processosController->salvarPartes(array($itemsDestinatario), $idProcesso);
                }
                //Percorre a lista de advogados salvando cada uma no db
                foreach ($items->destinatarioadvogados as $itemsAdvogados) {
                    $this->salvarAdvogados(array($itemsAdvogados));
                }
                $numeroIntimacoesProcessadas++;
            } else {
                $numeroIntimacoesRepetidas++;
            }
        }
        //Binding de dados para auditoria    
        $data = [
            'status_recebimento_intimacao' => $statusRecebimentoIntimacao,
            'numero_intimacoes_recebidas' => $numeroIntimacoesRecebidas,
            'numero_intimacoes_repetidas' => $numeroIntimacoesRepetidas,
            'numero_intimacoes_processadas' => $numeroIntimacoesProcessadas,
            'nomeArquivo' => $nomeArquivo,
            'usuario_id' => $usuario_id,
        ];
        $auditoriaRecebimentoIntimacoes = new AuditoriaRecebimentoIntimacoes();
        $auditoriaRecebimentoIntimacoes->registraProcessamentoIntimacoes($data);
        $data['titulo'] = 'Intimações';
        return view('dashboard', $data);
    }

}
