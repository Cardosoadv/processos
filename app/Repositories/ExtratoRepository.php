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
        $despesas = $this->pagtoDespesasModel
            ->distinct()
            ->select('pd.id_pgto_despesa as id, pd.pagamento_despesa_dt as data, d.despesa as descricao, (pd.valor * -1) as valor, pd.rateio as rateio')
            ->from('fin_pgto_despesas as pd')
            ->where('pd.conta_id', $conta_id)
            ->where('pd.deleted_at is null')
            ->join('fin_despesas as d', 'd.id_despesa = pd.despesa_id', 'left')
            ->findAll();

        foreach ($despesas as &$despesa) {
            $rateioArray = []; // Inicializa $rateioArray fora do loop interno

            // Verifica se $despesa['rateio'] é uma string antes de decodificar
            if (is_string($despesa['rateio'])) {
                $rateio = json_decode($despesa['rateio'], true);

                // Verifica se $rateio é um array antes de iterar
                if (is_array($rateio)) {
                    foreach ($rateio as $item) {
                        $rateioArray[] = [
                            'id' => $item['id'],
                            'valor' => floatval(-$item['valor']),
                        ];
                    }
                }
            }
            $despesa['rateio'] = $rateioArray; // Substitui o valor original pelo array processado
        }

        return $despesas;
    }

    public function getReceitasPorConta($conta_id)
    {
        // Buscar receitas da conta
        $receitas = $this->pagtoReceitasModel
            ->distinct()
            ->select('pr.id_pgto_receita as id, pr.pagamento_receita_dt as data, r.receita as descricao, pr.valor as valor, pr.rateio as rateio')
            ->from('fin_pgto_receitas as pr')
            ->where('pr.conta_id', $conta_id)
            ->where('pr.deleted_at is null')
            ->join('fin_receitas as r', 'r.id_receita = pr.receita_id', 'left')
            ->findAll();

        foreach ($receitas as &$receita) {
            $rateioArray = []; // Inicializa $rateioArray fora do loop interno

            // Verifica se $despesa['rateio'] é uma string antes de decodificar
            if (is_string($receita['rateio'])) {
                $rateio = json_decode($receita['rateio'], true);

                // Verifica se $rateio é um array antes de iterar
                if (is_array($rateio)) {
                    foreach ($rateio as $item) {
                        $rateioArray[] = [
                            'id' => $item['id'],
                            'valor' => floatval($item['valor']),
                        ];
                    }
                }
            }
            $receita['rateio'] = $rateioArray; // Substitui o valor original pelo array processado
        }

        return $receitas;
    }


    public function getTransferenciasDePorConta($conta_id)
    {
        // Buscar transferencias da conta
        $transferencias = $this->transferenciaModel
            ->distinct()
            ->select('id_transferencia as id, data_transferencia as data, transferencia as descricao, (valor * -1) as valor, NULL as rateio')
            ->where('id_conta_origem', $conta_id)
            ->where('deleted_at is null')
            ->findAll();
        return $transferencias;
    }

    public function getTransferenciasParaPorConta($conta_id)
    {
        // Buscar transferencias da conta
        $transferencias = $this->transferenciaModel
            ->distinct()
            ->select('id_transferencia as id, data_transferencia as data, transferencia as descricao, valor, NULL as rateio')
            ->where('id_conta_destino', $conta_id)
            ->where('deleted_at is null')
            ->findAll();
        return $transferencias;
    }
}
