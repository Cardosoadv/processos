<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProcessosMovimentosContagens extends Migration
{
    private $Fields =[
        'numero_movimentos'         => ['type' => 'int', 'null' => true, 'after' =>'usuario_id'],
        'movimentos_salvos'         => ['type' => 'int', 'null' => true, 'after' =>'numero_movimentos'],
        'movimentos_ignorados'      => ['type' => 'int', 'null' => true, 'after' =>'movimentos_salvos'],
        'json_filename'             => ['type' => 'varchar', 'constraint' => 50, 'null' => true, 'after' =>'movimentos_ignorados'],
        'erro'                      => ['type' => 'varchar', 'constraint' => 150, 'null' => true, 'after' =>'json_filename'],
        
    ];
    
    public function up()
    {
        $this->forge->addColumn('processos_monitorados', $this->Fields);
    }

    public function down()
    {
        $this->forge->dropColumn('processos_monitorados', $this->Fields);
    }
}
