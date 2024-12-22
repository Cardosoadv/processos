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
use App\Models\ProcessosPartesModel;

class Intimacoes extends BaseController
{
    public function index(){

        $oab = "164136";
        $ufOab = "MG";

        $params = [
        'numeroOab' => $oab,
        'ufOab' => $ufOab
        ];

        $receberIntimacoes = new ReceberIntimacoes();
        $receberIntimacoes->getIntimacoes($params);
    }

    /**
     * Efetua o tratamento das intimações organizando os dados e salvandos nas tabelas corretas
     */
    public function parseIntimacao(array $data, $filename){

        $intimacoesModel = new IntimacoesModel();
        $usuario_id = user_id();
        $nomeArquivo = $filename;
        $statusRecebimentoIntimacao = $data['message'];
        $numeroIntimacoesRecebidas = $data['count'];
        $numeroIntimacoesRepetidas = 0;
        $numeroIntimacoesProcessadas = 0;

        if ($numeroIntimacoesRecebidas === 0){
            return;
        }
        //Percorre os itens da intimação
        foreach($data['items'] as $items){
            
            //checa se a intimação já consta no db
            if ($intimacoesModel->intimacaoJaExiste($items['id']) === false){
                
                //Verifica se existe e salva ou atualiza o processo no db
                $idProcesso = $this->salvarProcessos($items);

                //Salva ou atualiza a intimação no db
                $this->salvarIntimacao($items);

                //Percorre a lista de destinários salvando cada uma no db
                foreach($items['destinatarios'] as $itemsDestinatario){
                    //Salva ou atualiza o destinatário no db
                    $this->salvarDestinatarios($itemsDestinatario);
					//TODO Criar rotina salvar partes do processo!
                    $this->salvarPartes($itemsDestinatario, $idProcesso);
                    
                }
                //Percorre a lista de advogados salvando cada uma no db
                foreach($items['destinatarioadvogados'] as $itemsAdvogados){
                    $this->salvarAdvogados($itemsAdvogados);
                }
                $numeroIntimacoesProcessadas++;
            }else{
                $numeroIntimacoesRepetidas++;
            }            
        }
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
}
private function salvarProcessos($processos)
{
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
        'status'                   => $processos['status'],
        'risco'                    => $processos['risco'] ?? 'Possível', // Operador de coalescência nula (??) já está correto.
        'numeroprocessocommascara' => $processos['numeroprocessocommascara'],
    ];

    // Usa o método insert() para inserir os dados.
    $processosModel->insert($data);

    // Retorna o ID do processo inserido.
    return $processosModel->insertID();

    }

    private function salvarIntimacao($intimacao){
        $intimacoesModel = new IntimacoesModel();
        $converterData = new ConverterData();

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
            'motivo_cancelamento'               => $intimacao['motivo_cancelamento'],
            'data_cancelamento'                 => $intimacao['data_cancelamento'],
            'datadisponibilizacao'              => $converterData->dataParaBancoDados($intimacao['datadisponibilizacao']),
            'dataenvio'                         => $converterData->dataParaBancoDados($intimacao['dataenvio']),
            'meiocompleto'                      => $intimacao['meiocompleto'],
        ];
        $intimacoesModel->insert($data);
    }

    private function salvarDestinatarios($destinatario){

        $intimacoesDestinatariosModel = new IntimacoesDestinatariosModel();
        $data = [
            'nome'               => $destinatario['nome'],
            'polo'               => $destinatario['polo'],
            'comunicacao_id'     => $destinatario['comunicacao_id'],
        ];
        $intimacoesDestinatariosModel->save($data);
    }

    /**
     * Salva as partes do processo.
     */
    private function salvarPartes(array $parte, int $idProcesso)
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
        private function salvarAdvogados($advogado){

            $intimacoesAdvogadosModel = new IntimacoesAdvogadosModel();
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
}
