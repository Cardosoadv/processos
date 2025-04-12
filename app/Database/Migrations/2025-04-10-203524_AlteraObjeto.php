<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlteraObjeto extends Migration
{
    public function up()
    {
        $fields = [
            'cidade'        => ['type' => 'varchar', 'constraint' => '100','null' => true],
            'bairro'        => ['type' => 'varchar', 'constraint' => '100','null' => true],
            'quadra'        => ['type' => 'varchar', 'constraint' => '10','null' => true],
            'lote'          => ['type' => 'varchar', 'constraint' => '10','null' => true],
            'inscricao'     => ['type' => 'varchar', 'constraint' => '20','null' => true],
            'cod_interno'   => ['type' => 'varchar', 'constraint' => '20','null' => true],
            'matricula'     => ['type' => 'varchar', 'constraint' => '10','null' => true],
            'cartorio'      => ['type' => 'varchar', 'constraint' => '50','null' => true],
        ];

        $this->forge->addColumn('processos_objeto', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('processos_objeto', 'cidade');
        $this->forge->dropColumn('processos_objeto', 'bairro');
        $this->forge->dropColumn('processos_objeto', 'quadra');
        $this->forge->dropColumn('processos_objeto', 'lote');
        $this->forge->dropColumn('processos_objeto', 'inscricao');
        $this->forge->dropColumn('processos_objeto', 'cod_interno');
        $this->forge->dropColumn('processos_objeto', 'matricula');
        $this->forge->dropColumn('processos_objeto', 'cartorio');
    }
}
