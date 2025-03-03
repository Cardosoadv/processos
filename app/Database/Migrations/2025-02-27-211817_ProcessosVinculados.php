<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProcessosVinculados extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_vinculo'                => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'id_processo_a'             => ['type' => 'int', 'constraint' => 11],
            'id_processo_b'             => ['type' => 'int', 'constraint' => 11],
            'tipo_vinculo'              => ['type' => 'varchar', 'constraint'=>150],
        ]);
        $this->forge->addPrimaryKey('id_vinculo');
        $this->forge->createTable('processos_vinculados');
    }

    public function down()
    {
        $this->forge->dropTable('processos_vinculados');
    }
}
