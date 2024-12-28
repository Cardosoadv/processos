<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProcessosNotas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_nota'                   => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'nota'                      => ['type' => 'text', 'null' => true],
            'link'                      => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'id_processo'               => ['type' => 'int', 'constraint' => 11],
            'comentario'                => ['type' => 'text', 'null' => true],
            'created_at'                => ['type' => 'datetime', 'null' => true],
            'updated_at'                => ['type' => 'datetime', 'null' => true],
            'deleted_at'                => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_nota');
        $this->forge->createTable('processos_notas');
    }

    public function down()
    {
        $this->forge->dropTable('processos_notas');
    }
}
