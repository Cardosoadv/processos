<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlteraObjetoEndereco extends Migration
{
    public function up()
    {
        $fields = [
            'logradouro'    => ['type' => 'varchar', 'constraint' => '150','null' => true],
            'numero'        => ['type' => 'varchar', 'constraint' => '10','null' => true],
            'complemento'   => ['type' => 'varchar', 'constraint' => '150','null' => true],
            
        ];
        $this->forge->addColumn('processos_objeto', $fields);

        $fields = [
            'dados'            => ['name'=>'comentarios', 'type' => 'text', 'null' => true, 'after' => 'complemento'],
            'created_at'       => ['type' => 'datetime', 'null' => true, 'after' => 'comentarios'],
            'updated_at'       => ['type' => 'datetime', 'null' => true, 'after' => 'created_at'],
            'deleted_at'       => ['type' => 'datetime', 'null' => true, 'after' => 'updated_at'],
        ];

        $this->forge->modifyColumn('processos_objeto', $fields);

    }

    public function down()
    {
        $this->forge->dropColumn('processos_objeto', 'logradouro');
        $this->forge->dropColumn('processos_objeto', 'numero');
        $this->forge->dropColumn('processos_objeto', 'complemento');

    }
}
