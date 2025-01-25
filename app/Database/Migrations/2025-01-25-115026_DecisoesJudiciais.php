<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DecisoesJudiciais extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_decisao'               => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'dados'                     => ['type' => 'json', 'null' => true],    
            'created_at'                => ['type' => 'datetime', 'null' => true],
            'updated_at'                => ['type' => 'datetime', 'null' => true],
            'deleted_at'                => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_decisao');
        $this->forge->createTable('decisoes_judiciais');
    }

    public function down()
    {
        $this->forge->dropPrimaryKey('decisoes_judiciais');
        $this->forge->dropTable('decisoes_judiciais');
    }
}
