<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RevisaoProcesso extends Migration
{
    public function up()
    {
        $fields = [
            'dataRevisao' => ['type' => 'date', 'null' => true, 'after'=>'cliente_id'],
            'encerrado'     => ['type' => 'tinyint', 'default'=> 0, 'after'=>'dataRevisao'],
        ];
        $this->forge->addColumn('processos', $fields);

        $tarefaField = [
            'detahes' => ['name'=> 'detalhes', 'type' => 'text', 'null' => true]
        ];
        $this->forge->modifyColumn('tarefas', $tarefaField);

    }

    public function down()
    {
        //
    }
}
