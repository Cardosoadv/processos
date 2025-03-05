<?php

namespace App\Repositories;



class ExtratoRepository
{
    protected $receitaModel;
    protected $despesaModel;
    protected $pagtoDespesasModel;
    protected $pagtoReceitasModel;

    public function __construct(){
        
        $this->receitaModel = model('Financeiro/FinanceiroReceitasModel');
        $this->despesaModel = model('Financeiro/FinanceiroDespesasModel');
        $this->pagtoDespesasModel = model('Financeiro/FinanceiroPagtoDespesasModel');
        $this->pagtoReceitasModel = model('Financeiro/FinanceiroPagtoReceitasModel');
    }



}