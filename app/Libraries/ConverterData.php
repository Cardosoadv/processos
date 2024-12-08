<?php

namespace App\Libraries;

use DateTime;


class ConverterData{

        //função para formatar a data. Ainda não foi testada.
        public function novaData($data)
        {
        $novaData = date('Y-m-d', strtotime($data));
        return $novaData;
        }
}