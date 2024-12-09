<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProcessosMonitorar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_monitoramento'          => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'numero_processo'           => ['type' => 'varchar', 'constraint'=>50, 'null' => true],
            'usuario_id'                => ['type' => 'int', 'constraint'=>11],
            'ultima_checagem'           => ['type' => 'datetime', 'null' => true],
            'created_at'                => ['type' => 'datetime', 'null' => true],
            'updated_at'                => ['type' => 'datetime', 'null' => true],
            'deleted_at'                => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_monitoramento');
        $this->forge->createTable('processos_monitorados');
    }

    public function down()
    {
        $this->forge->dropTable('processos_monitorados');
    }
}
