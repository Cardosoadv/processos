<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Auditoria extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'table_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'action_type' => [
                'type'       => 'ENUM',
                'constraint' => ['CREATE', 'UPDATE', 'DELETE'],
            ],
            'dados_antigos' => [
                'type' => 'json',
                'null' => true,
            ],
            'dados_novos' => [
                'type' => 'json',
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
            'updated_at'                  => ['type' => 'datetime', 'null' => true],
            'deleted_at'                  => ['type' => 'datetime', 'null' => true],
        ]);
        
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('auditoria');
    }

    public function down()
    {
        $this->forge->dropTable('auditoria');
    }
}
