<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataEncerraentoProcesso extends Migration
{
    public function up()
    {
        $fields = [
            "data_encerramento"            => ['type' => 'date', 'null' => true, 'after'=>'encerrado'],
        ];
        $this->forge->addColumn('processos', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('processos', 'data_encerramento');
    }
}
