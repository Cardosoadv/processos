<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TarefasAlteracao extends Migration
{

    private $Fields =[
        'status'         => ['type' => 'int', 'null' => true],
    ];
    public function up()
    {
        $this->forge->modifyColumn('tarefas', $this->Fields);
    }

    public function down()
    {
        //
    }
}
