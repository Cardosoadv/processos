<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Lerjson extends BaseController
{
    public function index()
    {
        
        $dir = WRITEPATH . "jsons/";
        $arquivos = scandir($dir);





        echo '<pre>';
        print_r($arquivos);

    }
}
