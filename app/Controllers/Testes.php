<?php

namespace App\Controllers;

use App\Services\EmailService;

class Testes extends BaseController
{
    public function index()
    {
        $emailService = new EmailService();
        $email =$emailService->sendEmail("fabianocardoso.adv@gmail.com", "Assunto", "Texto");

        echo '<pre>';
        var_dump($email);

    }
}