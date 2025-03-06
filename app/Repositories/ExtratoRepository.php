<?php

namespace App\Repositories;



class ExtratoRepository
{
    protected $receitaModel;
    protected $despesaModel;
    protected $pagtoDespesasModel;
    protected $pagtoReceitasModel;
    protected $transferenciaModel;

    public function __construct()
    {

        $this->receitaModel = model('Financeiro/FinanceiroReceitasModel');
        $this->despesaModel = model('Financeiro/FinanceiroDespesasModel');
        $this->pagtoDespesasModel = model('Financeiro/FinanceiroPagtoDespesasModel');
        $this->pagtoReceitasModel = model('Financeiro/FinanceiroPagtoReceitasModel');
        $this->transferenciaModel = model('Financeiro/FinanceiroTransferenciasModel');
    }

    public function getDespesasPorConta($conta_id)
    {
        // Buscar despesas da conta

        // Buscar despesas da conta
        $despesas = $this->pagtoDespesasModel
            ->select('pd.pagamento_despesa_dt as data, d.despesa as descricao, pd.valor as valor, pd.rateio as rateio')
            ->from('fin_pgto_despesas as pd')  // Alias the main table
            ->where('pd.conta_id', $conta_id)
            ->join('fin_despesas as d', 'd.id_despesa = pd.despesa_id', 'right')
            ->findAll();

        return $despesas;
    }

    public function getReceitasPorConta($conta_id)
    {
        // Buscar receitas da conta
        $receitas = $this->pagtoReceitasModel
            ->select('pr.pagamento_receita_dt as data, r.receita as descricao, pr.valor as valor, pr.rateio as rateio')
            ->from('fin_pgto_receitas as pr')  // Alias the main table
            ->where('pr.conta_id', $conta_id)
            ->join('fin_receitas as r', 'r.id_receita = pr.receita_id', 'right')
            ->findAll();
        return $receitas;
    }

    public function getTransferenciasDePorConta($conta_id)
    {
        // Buscar transferencias da conta
        $transferencias = $this->transferenciaModel
            ->select('data_transferencia as data, transferencia as descricao, valor, NULL as rateio')
            ->where('id_conta_origem', $conta_id)
            ->findAll();
        return $transferencias;
    }

    public function getTransferenciasParaPorConta($conta_id)
    {
        // Buscar transferencias da conta
        $transferencias = $this->transferenciaModel
            ->select('data_transferencia as data, transferencia as descricao, valor, NULL as rateio')
            ->where('id_conta_destino', $conta_id)
            ->findAll();
        return $transferencias;
    }
}
