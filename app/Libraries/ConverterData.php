<?php

namespace App\Libraries;



class ConverterData{

        //função para formatar a data. Ainda não foi testada.
        public function dataParaBancoDados($data)
        {
        $novaData = date('Y-m-d', strtotime($data));
        return $novaData;
        }
}