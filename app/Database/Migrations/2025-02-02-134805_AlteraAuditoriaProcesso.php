<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlteraAuditoriaProcesso extends Migration
{
    public function up()
    {
        $Field = [
            'changes' => ['name'=> 'dados_novos', 'type' => 'json', 'null' => true]
        ];
        $this->forge->modifyColumn('auditoria_processo', $Field);
        $dados_antigos = [
            'dados_antigos' => ['type' => 'json', 'null' => true, 'after'=>'dados_novos']
        ];
        $this->forge->addColumn('auditoria_processo', $dados_antigos);
    }


    public function down()
    {
        //
    }
}
