<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProcessoAuditTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_audit' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'processo_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'action_type' => [
                'type'       => 'ENUM',
                'constraint' => ['CREATE', 'UPDATE', 'DELETE'],
            ],
            'changes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_audit', true);
        $this->forge->createTable('auditoria_processo');
    }

    public function down()
    {
        $this->forge->dropTable('processo_audits');
    }
}