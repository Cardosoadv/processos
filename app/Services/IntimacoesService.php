<?php

namespace App\Services;

use App\Libraries\ConverterData;
use App\Libraries\ReceberIntimacoes;
use App\Models\IntimacoesModel;
use App\Models\IntimacoesAdvogadosModel;
use App\Models\IntimacoesDestinatariosModel;

class IntimacoesService
{
    private $intimacoesModel;
    private $destinatariosModel;
    private $advogadosModel;
    private $converterData;
    private $processoService;

    public function __construct()
    {
        $this->intimacoesModel = new IntimacoesModel();
        $this->destinatariosModel = new IntimacoesDestinatariosModel();
        $this->advogadosModel = new IntimacoesAdvogadosModel();
        $this->converterData = new ConverterData();
        $this->processoService = new ProcessoService();
    }

    public function listarIntimacoes()
    {
        return $this->intimacoesModel->orderBy('data_disponibilizacao', 'DESC')->findAll();
    }

    public function buscarIntimacoes($params)
    {
        $receberIntimacoes = new ReceberIntimacoes();
        return $receberIntimacoes->getIntimacoes($params);
    }

    public function processarIntimacoes($data, $filename, $userId)
    {
        $estatisticas = [
            'status_recebimento_intimacao' => $data['message'],
            'numero_intimacoes_recebidas' => $data['count'],
            'numero_intimacoes_repetidas' => 0,
            'numero_intimacoes_processadas' => 0,
            'nomeArquivo' => $filename,
            'usuario_id' => $userId,
        ];

        if ($estatisticas['numero_intimacoes_recebidas'] === 0) {
            return $estatisticas;
        }

        foreach ($data['items'] as $item) {
            if (!$this->intimacoesModel->intimacaoJaExiste($item['id'])) {
                $this->processarItem($item);
                $estatisticas['numero_intimacoes_processadas']++;
            } else {
                $estatisticas['numero_intimacoes_repetidas']++;
            }
        }

        $this->registrarAuditoria($estatisticas);
        return $estatisticas;
    }

    private function processarItem($item)
    {
        // Preparar dados do processo
        $dadosProcesso = [
            'siglaTribunal'                 => $item['siglaTribunal'],
            'nomeOrgao'                     => $item['nomeOrgao'],
            'numero_processo'               => $item['numero_processo'],
            'link'                          => $item['link'],
            'tipoDocumento'                 => $item['tipoDocumento'],
            'codigoClasse'                  => $item['codigoClasse'],
            'ativo'                         => $item['ativo'],
            'status'                        => $item['status'] ?? 'P',
            'risco'                         => $item['risco'] ?? 'Possível',
            'numeroprocessocommascara'      => $item['numeroprocessocommascara'],
        ];

        $jaExisteProcesso = $this->processoService->processoJaExiste($item['numero_processo']);
        
        if ($jaExisteProcesso) {
            $idProcesso = $jaExisteProcesso['id_processo'];
        }else{
        // Usar o ProcessoService existente para salvar o processo
        $idProcesso = $this->processoService->salvarProcesso($dadosProcesso, null);
        }
        
        $this->salvarIntimacao($item);
        
        foreach ($item['destinatarios'] as $destinatario) {
            $this->salvarDestinatario($destinatario);
            // Usar o ProcessoService existente para salvar as partes
            $this->processoService->salvarPartes($destinatario, $idProcesso);
        }

        foreach ($item['destinatarioadvogados'] as $advogado) {
            $this->salvarAdvogado($advogado);
        }
    }

    private function salvarIntimacao($intimacao)
    {
        $data = [
            'id_intimacao' => $intimacao['id'],
            'data_disponibilizacao' => $this->converterData->dataParaBancoDados($intimacao['data_disponibilizacao']),
            'tipoComunicacao' => $intimacao['tipoComunicacao'],
            'texto' => $intimacao['texto'],
            'numero_processo' => $intimacao['numero_processo'],
            'meio' => $intimacao['meio'],
            'link' => $intimacao['link'],
            'numeroComunicacao' => $intimacao['numeroComunicacao'],
            'hash' => $intimacao['hash'],
            'motivo_cancelamento' => $intimacao['motivo_cancelamento'] ?? null,
            'data_cancelamento' => $intimacao['data_cancelamento'] ?? null,
            'datadisponibilizacao' => $this->converterData->dataParaBancoDados($intimacao['datadisponibilizacao']),
            'dataenvio' => $this->converterData->dataParaBancoDados($intimacao['datadisponibilizacao']),
            'meiocompleto' => $intimacao['meiocompleto'],
        ];
        $this->intimacoesModel->insert($data);
    }

    private function salvarDestinatario($destinatario)
    {
        $data = [
            'nome' => $destinatario['nome'],
            'polo' => $destinatario['polo'],
            'comunicacao_id' => $destinatario['comunicacao_id'],
        ];
        $this->destinatariosModel->save($data);
    }

    private function salvarAdvogado($advogado)
    {
        $data = [
            'id' => $advogado['id'],
            'comunicacao_id' => $advogado['comunicacao_id'],
            'advogado_id' => $advogado['advogado']['id'],
            'advogado_nome' => $advogado['advogado']['nome'],
            'advogado_oab' => $advogado['advogado']['numero_oab'],
            'advogado_oab_uf' => $advogado['advogado']['uf_oab'],
            'created_at' => $advogado['created_at'],
            'updated_at' => $advogado['updated_at'],
        ];
        $this->advogadosModel->save($data);
    }

    private function registrarAuditoria($estatisticas)
    {
        $auditoriaService = new AuditoriaService();
        $auditoriaService->registrarProcessamentoIntimacoes($estatisticas);
    }

    public function buscarIntimacoesPorPeriodo($dias)
    {
        $hoje = date('Y-m-d', time());
        $dataInicial = date('Y-m-d', strtotime('-'.$dias.' days'));
        return $this->intimacoesModel->getIntimacoesdoPeriodo($dataInicial, $hoje);
    }
}