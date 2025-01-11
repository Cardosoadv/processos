<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TituloProcesso extends Migration
{
    public function up()
    {
        $fields = [
                        'titulo_processo' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after'=>'numero_processo'],
        ];
        $this->forge->addColumn('processos', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('processos', 'titulo_processo');
    }
}
