<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AuditoriaCliente extends Migration
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
            'cliente_id' => [
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
        ]);
        
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('auditoria_cliente');
    }

    public function down()
    {
        $this->forge->dropTable('auditoria_cliente');
    }
}
