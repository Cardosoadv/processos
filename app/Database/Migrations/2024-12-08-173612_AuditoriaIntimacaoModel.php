<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AuditoriaIntimacaoModel extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                            => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'status_recebimento_intimacao'  => ['type' => 'varchar', 'constraint' => 10, 'null' => true],
            'numero_intimacoes_recebidas'   => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'numero_intimacoes_repetidas'   => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'numero_intimacoes_processadas' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'nomeArquivo'                   => ['type' => 'varchar', 'constraint' => 150, 'null' => true],
            'usuario_id'                    => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'created_at'                    => ['type' => 'datetime', 'null' => true],
            'updated_at'                    => ['type' => 'datetime', 'null' => true],
            'deleted_at'                    => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('auditoria_intimacao');
    }

    public function down()
    {
        $this->forge->dropTable('auditoria_intimacao');
    }
}
