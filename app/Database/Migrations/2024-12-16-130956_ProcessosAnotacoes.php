<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProcessosAnotacoes extends Migration
{
    private $Fields =[
        'dataDistribuicao'          => ['type' => 'date', 'null' => true, 'after' =>'risco'],
        'valorCausa'                => ['type' => 'double', 'null' => true, 'after' =>'dataDistribuicao'],
        'resultado'                 => ['type' => 'ENUM', 'constraint'=>['Não Finalizado', 'Sucesso', 'Sucesso Parcial', 'Derrota'], 'default' => 'Não Finalizado', 'after' =>'dataDistribuicao'],
        'valorCondenacao'           => ['type' => 'double', 'null' => true, 'after' =>'resultado'],
        'comentario'                => ['type' => 'text', 'null' => true, 'after' =>'valorCondenacao'],
        ];
    
    public function up()
    {

        /**
         * Adiciona colunas a Tabela de Processos
         */
        $this->forge->addColumn('processos', $this->Fields);

        /**
         * Cria Tabela de Anotações de Processos
         */
        $this->forge->addField([
            'id_anotacao'      => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'titulo'           => ['type' => 'varchar', 'constraint' => 150,'null' => true],
            'anotacao'         => ['type' => 'text','null' => true],
            'privacidade'      => ['type' => 'int', 'constraint' => 11,'default' => 1], // 1 - Privado, 2 - Envolvidos, 3 - Público
            'processo_id'      => ['type' => 'int', 'constraint' => 11],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_anotacao');
        $this->forge->addForeignKey('processo_id', 'processos', 'id_processo', '', 'CASCADE', 'FK_anotacao_processos');
        $this->forge->createTable('processos_anotacao');

    }

    public function down()
    {
        $this->forge->dropForeignKey('processos_anotacao','FK_anotacao_processos');
        $this->forge->dropColumn('processos', $this->Fields);
        $this->forge->dropTable('processos_anotacao');
    }
}
