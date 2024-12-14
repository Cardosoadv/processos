<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProcessosModel;
use CodeIgniter\HTTP\ResponseInterface;

class Processos extends BaseController
{
    public function index()
    {
        $data = [
            'img'       =>  'vazio.png',
            'titulo'    => 'Processos',
            
        ];

        $table = new \CodeIgniter\View\Table();


        $processosModel = model('ProcessosModel');
        $processos = $processosModel
                ->findAll();

        $table->setHeading(['Numero Processo', 'Tribunal', 'Orgão', 'Ações']);        
        $template = [
                        'table_open' => '<table class="table table-hover">',
                        'cell_start'  => '<td class="col-md-3">', // Define a largura da primeira coluna como 3/12 do container
                        'cell_alt_start' => '<td class="col">', // Deixa as outras colunas com largura automática
                    ];
                
        $table->setTemplate($template);   
        
        foreach ($processos as $processo) {
            $table->addRow([
                $processo['numeroprocessocommascara'],
                $processo['siglaTribunal'],
                $processo['nomeOrgao'],
                '<div class="btn-group">
                    <a href="' . base_url('processos/editar/' . $processo['id_processo']) . '" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="' . base_url('processos/excluir/' . $processo['id_processo']) . '" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Excluir
                    </a>
                </div>'
            ]);
        }

        $data['table'] = $table->generate();
        return view('processos/processos', $data);
    }
}
