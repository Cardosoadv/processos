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
        $etiquetasModel = model('EtiquetasModel');
        $processosModel = model('ProcessosModel');
        $data = $this->request->getJSON();
        $nome = $data->nome;
        $cor = preg_replace('/^#/', '', $data->cor);
        $id_processo = $data->id_processo;
        if($nome == null || $cor == null || $id_processo == null){
            return $this->response->setJSON(['success' => false]);
        }
        if($etiquetasModel->where('nome', $nome)->first()){
            return $this->response->setJSON(['success' => false]);
        }
        try{
        $etiquetasModel->insert([
            'nome' => $nome,
            'cor' => $cor,
        ]);
        $idEtiqueta = $etiquetasModel->InsertID();
        $processosModel->addEtiqueta($id_processo,$idEtiqueta);
        }catch(\Exception $e){
            return $this->response->setJSON(['success' => false, 'error' => $e->getMessage()]);
        }
        return $this->response->setJSON(['success' => true]);
    }
}
