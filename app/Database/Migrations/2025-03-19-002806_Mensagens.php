<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Mensagens extends Migration
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
            'remetente_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'destinatario_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'conteudo' => [
                'type' => 'TEXT',
            ],
            'data_envio' => [
                'type' => 'DATETIME',
            ],
            'data_leitura' => [
                'type' => 'DATETIME',
                'null' => true, // Permite que a data de leitura seja nula inicialmente
            ],
            'assunto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('remetente_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('destinatario_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mensagens');
    }

    public function down()
    {
        $this->forge->dropTable('mensagens');
    }
}