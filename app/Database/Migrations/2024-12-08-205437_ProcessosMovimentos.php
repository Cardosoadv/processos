<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProcessosMovimentos extends Migration
{
    public function up()
    { 
        $this->forge->addField([
            'id_movimento'              => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'numero_processo'           => ['type' => 'varchar', 'constraint'=>50, 'null' => true],
            'nome'                      => ['type' => 'varchar', 'constraint'=>50, 'null' => true],
            'codigo'                    => ['type' => 'int', 'constraint'=>11],
            'valor'                     => ['type' => 'int', 'constraint'=>11],
            'descricao_complemento'     => ['type' => 'varchar', 'constraint'=>150, 'null' => true],
            'nome_complemento'          => ['type' => 'varchar', 'constraint'=>150, 'null' => true],
            'dataHora'                  => ['type' => 'timestamp', 'null' => true],
            'created_at'                => ['type' => 'datetime', 'null' => true],
            'updated_at'                => ['type' => 'datetime', 'null' => true],
            'deleted_at'                => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_movimento');
        $this->forge->createTable('processos_movimentos');
    }

    public function down()
    {
        $this->forge->dropTable('processos_movimentos');
    }
}
