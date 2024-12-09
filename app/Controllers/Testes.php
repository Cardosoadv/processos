<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ReceberMovimentosDatajud;

class Testes extends BaseController
{
    public function index()
    {
        $numeroProcesso = "50391641120198130024";
        $receberMovimentos = new ReceberMovimentosDatajud();
        $receberMovimentos->receberMovimentos($numeroProcesso);
        
    }
}
