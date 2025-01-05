<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProcessoCliente extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_cliente'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'tipo_cliente'          => ['type' => 'ENUM', 'constraint' => ['F', 'J'], 'default' => 'F', 'comment' => 'F = Física, J = Jurídica'],
            'nome'                  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'documento'             => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true, 'null' => false, 'comment' => 'CPF para física, CNPJ para jurídica'],
            'email'                 => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'telefone'              => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'endereco'              => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'complemento'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'cep'                   => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'cidade'                => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'uf'                    => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'razao_social'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'comment' => 'Apenas para pessoa jurídica'],
            'ativo'                 => ['type' => 'TINYINT', 'default' => 1],
            'created_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'            => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_cliente');
        $this->forge->createTable('clientes');

        $filds = [
            "cliente_id"            => ['type' => 'INT', 'constraint' => 11, 'null' => true, 'after'=>'comentario'],
        ];
        $this->forge->addColumn('processos', $filds);
        
    }

    public function down()
    {
        $this->forge->dropTable('clientes');
        $this->forge->dropColumn('processos', 'cliente_id');
    }
}
