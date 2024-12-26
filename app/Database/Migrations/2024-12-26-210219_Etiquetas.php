<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Etiquetas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_etiqueta'               => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'nome'                      => ['type' => 'varchar', 'constraint'=>50, 'null' => true],
            'cor'                       => ['type' => 'varchar', 'constraint'=>10, 'null' => true],
            'created_at'                => ['type' => 'datetime', 'null' => true],
            'updated_at'                => ['type' => 'datetime', 'null' => true],
            'deleted_at'                => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_etiqueta');
        $this->forge->createTable('etiquetas');

        $this->forge->addField([
            'etiqueta_id'               => ['type' => 'int', 'constraint'=>11],
            'processo_id'               => ['type' => 'int', 'constraint'=>11],
        ]);

        $this->forge->createTable('processos_etiquetas');
    }



    public function down()
    {
        $this->forge->dropTable('etiquetas');
        $this->forge->dropTable('processos_etiquetas');
    }
}
