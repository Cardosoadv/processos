<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tarefas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_tarefa'                   => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'tarefa'                      => ['type' => 'text', 'null' => true],
            'prazo'                       => ['type' => 'date', 'null'=>true],
            'prioridade'                  => ['type' => 'int', 'constraint' => 11, 'default'=>5],
            'detahes'                     => ['type' => 'text', 'null' => true],
            'responsavel'                 => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'processo_id'                 => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'status'                      => ['type' => 'tinyint', 'default'=>0],
            'created_at'                  => ['type' => 'datetime', 'null' => true],
            'updated_at'                  => ['type' => 'datetime', 'null' => true],
            'deleted_at'                  => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_tarefa');
        $this->forge->createTable('tarefas');
    }

    public function down()
    {
        $this->forge->dropTable('tarefas');
    }
}
