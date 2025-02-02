<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlteraCliente extends Migration
{
    public function up()
    {
        $fields = [
            'dataAquisicao' => [
                'type' => 'DATE',
                'null' => true,
            ],
        ];
        $this->forge->addColumn('clientes', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clientes', 'dataAquisicao');
    }
}
