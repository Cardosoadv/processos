<?php

namespace App\Controllers;

use App\Services\EmailService;

class Testes extends BaseController
{
    public function index()
    {
        
        $data =["fabianocardoso.adv@gmail.com"];


        return $this->loadView('welcome_message', $data);

    }
}