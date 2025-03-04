<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FinTransferencias extends Migration
{
    public function up()
    {
        /*
        * Tabela de Transferencias
        */
        $this->forge->addField([
            'id_transferencia'            => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'transferencia'               => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'data_transferencia'          => ['type' => 'date', 'null' => false],
            'id_conta_origem'             => ['type' => 'int', 'constraint' => 11, 'null' => false],
            'id_conta_destino'            => ['type' => 'int', 'constraint' => 11, 'null' => false],
            'valor'                       => ['type' => 'decimal', 'constraint' => '10,2', 'null' => false],
            'comentarios'                 => ['type' => 'text', 'null' => true],
            'created_at'                  => ['type' => 'datetime', 'null' => true],
            'updated_at'                  => ['type' => 'datetime', 'null' => true],
            'deleted_at'                  => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_transferencia');
        $this->forge->createTable('fin_transferencias');
    }

    public function down()
    {
        $this->forge->dropTable('fin_transferencias');
    }
}
