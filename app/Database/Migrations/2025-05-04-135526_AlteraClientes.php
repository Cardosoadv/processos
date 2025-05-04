<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlteraClientes extends Migration
{
    public function up()
    {
        $fields = [
            'usuario_id' => [
                'type' => 'int',
                'constraint' => 11,
                'null' => true,
                'after' => 'ativo'
            ],
        ];
        $this->forge->addColumn('clientes', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clientes', 'usuario_id');
    }
    }