<?php

namespace App\Libraries;

class Permissions{

    public function permission(){
        $permission['processos']    = (auth()->user()->can('module.processos') || auth()->user()->can('exclusive.construtiva') );
        $permission['clientes']     = (auth()->user()->can('module.clientes'));
        $permission['tarefas']      = (auth()->user()->can('module.tarefas'));
        $permission['financeiro']   = (auth()->user()->can('module.financeiro'));
        $permission['intimacoes']   = (auth()->user()->can('module.intimacoes'));
        return $permission;
    }

}
