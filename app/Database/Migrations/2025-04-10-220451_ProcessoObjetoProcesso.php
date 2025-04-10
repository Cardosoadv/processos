<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProcessoObjetoProcesso extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'objeto_id'      => ['type' => 'int', 'constraint'=>11],
            'processo_id'    => ['type' => 'int', 'constraint'=>11],
        ]);

        $this->forge->createTable('processos_objeto_processo');
    }

    public function down()
    {
        $this->forge->dropTable('processos_objeto_processo');
    }
}
