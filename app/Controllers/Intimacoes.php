<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\IntimacoesService;
use App\Services\ProcessosService;
use App\Services\AuditoriaService;

class Intimacoes extends BaseController
{
    private $intimacoesService;
    private $processosService;
    private $auditoriaService;

    public function __construct()
    {
        $this->intimacoesService = new IntimacoesService();
        $this->auditoriaService = new AuditoriaService();
    }

    public function index()
    {
        $data['titulo'] = 'Intimações';
        $data['intimacoes'] = $this->intimacoesService->listarIntimacoes();

        return $this->loadView('intimacoes', $data);
    }

    public function receberIntimacoes()
    {
        $params = $this->montarParametrosConsulta("61061", "MG");
        $this->intimacoesService->buscarIntimacoes($params);
        return redirect()       ->back()
                                ->with('success', 'Intimações recebidas com sucesso');
    }

    public function receberIntimacoesFabiano()
    {
        $params = $this->montarParametrosConsulta("164136", "MG");
        $this->intimacoesService->buscarIntimacoes($params);
        return redirect()       ->back()
                                ->with('success', 'Intimações recebidas com sucesso');
    }

    public function processo($numeroProcesso)
    {
        $params = ['numeroProcesso' => $numeroProcesso];
        $this->intimacoesService->buscarIntimacoes($params);
    }

    public function parseIntimacao(array $data, $filename)
    {
        $resultado = $this->intimacoesService->processarIntimacoes($data, $filename, user_id());
        return redirect()->to(base_url('intimacoes'));
    }

    public function intimacoesPorPeriodo($dias)
    {
        $intimacoes = $this->intimacoesService->buscarIntimacoesPorPeriodo($dias);
        return $this->response->setJSON($intimacoes);
    }

    private function montarParametrosConsulta($oab, $ufOab)
    {
        $hoje = date('Y-m-d', time());
        $mesPassado = date('Y-m-d', strtotime('-30 days'));
        
        return [
            'numeroOab' => $oab,
            'ufOab' => $ufOab,
            'dataDisponibilizacaoInicio' => $mesPassado,
            'dataDisponibilizacaoFim' => $hoje,
        ];
    }
}