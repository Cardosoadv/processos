<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;

class Index extends BaseController
{
    public function index()
    {
        $data = [
            'titulo' => 'Financeiro',
        ];
        return view('financeiro/index', $data);
    }
}