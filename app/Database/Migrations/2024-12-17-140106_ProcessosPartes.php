<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProcessosPartes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_parte'                  => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'nome'                      => ['type' => 'varchar', 'constraint'=>150],
            'polo'                      => ['type' => 'varchar', 'constraint'=>5],
            'cliente'                   => ['type' => 'tinyint', 'default' => 0],
            'created_at'                => ['type' => 'datetime', 'null' => true],
            'updated_at'                => ['type' => 'datetime', 'null' => true],
            'deleted_at'                => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_parte');
        $this->forge->createTable('processos_partes');

        $this->forge->addField([
            'id_parte'                  => ['type' => 'int', 'constraint' => 11],
            'id_processo'               => ['type' => 'int', 'constraint' => 11],
        ]);
        $this->forge->createTable('processos_partes_dos_processos');



    }

    public function down()
    {
        $this->forge->dropTable('processos_partes');
        $this->forge->dropTable('processos_partes_dos_processos');
    }
}
