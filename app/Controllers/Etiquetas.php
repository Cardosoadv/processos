<?php

namespace App\Controllers;

use App\Models\EtiquetasModel;

class Etiquetas extends BaseController
{
    public function index(){
        
    }

    public function listar()
    {
        $etiquetasModel = new EtiquetasModel();
        $etiquetas = $etiquetasModel->findAll();
        return $this->response->setJSON(['SugestaoEtiquetas' => $etiquetas]);
    }

    public function adicionar()
    {
        $etiquetasModel = new EtiquetasModel();
        $data = $this->request->getJSON();
        $etiquetas = $etiquetasModel->findAll();
        return $this->response->setJSON(['SugestaoEtiquetas' => $etiquetas]);
    }
}
