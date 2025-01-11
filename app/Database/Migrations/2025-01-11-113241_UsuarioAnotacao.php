<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsuarioAnotacao extends Migration
{
    public function up()
    {
        $fields = [
            'user_id' => ['type' => 'int', 'constraint' => 11, 'null' => false, 'after'=>'privacidade'],
        ];
        $this->forge->addColumn('processos_anotacao', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('processos_anotacao', 'user_id');
    }
}
