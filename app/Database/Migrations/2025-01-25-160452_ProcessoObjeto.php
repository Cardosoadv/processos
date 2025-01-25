<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProcessoObjeto extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_objeto'        => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'dados'            => ['type' => 'json', 'null' => true],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_objeto');
        $this->forge->createTable('processos_objeto');

    }
    

    public function down()
    {
        $this->forge->dropPrimaryKey('processos_objeto');
        $this->forge->dropTable('processos_objeto');
    }
}
