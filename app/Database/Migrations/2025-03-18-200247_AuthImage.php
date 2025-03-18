<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AuthImage extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                   => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'user_id'              => ['type' => 'int', 'constraint' => 11],
            'image_path'           => ['type' => 'varchar', 'constraint' => '120', 'null' => true],
            ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('auth_image');

    }

    public function down()
    {
        $this->forge->dropTable('auth_image');
    }
}
