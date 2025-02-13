<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Contas extends Migration
{
    public function up()
    {

        /*
        * Tabela de contas
        */
        $this->forge->addField([
            'id_conta'                    => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'conta'                       => ['type' => 'varchar','constraint' => 50, 'null' => true],
            'banco'                       => ['type' => 'varchar','constraint' => 50, 'null' => true],
            'agencia'                     => ['type' => 'varchar','constraint' => 50, 'null' => true],
            'numero_conta'                => ['type' => 'varchar','constraint' => 50, 'null' => true],
            'pix'                         => ['type' => 'varchar','constraint' => 50, 'null' => true],
            'comentarios'                 => ['type' => 'text', 'null' => true],
            'created_at'                  => ['type' => 'datetime', 'null' => true],
            'updated_at'                  => ['type' => 'datetime', 'null' => true],
            'deleted_at'                  => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_conta');
        $this->forge->createTable('fin_contas');

        
        /*
        * Tabela de categorias
        */
        $this->forge->addField([
            'id_categoria'                    => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'categoria'                       => ['type' => 'varchar','constraint' => 50, 'null' => true],
            'cor'                             => ['type' => 'varchar','constraint' => 50, 'null' => true],
            'comentarios'                     => ['type' => 'text', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_categoria');
        $this->forge->createTable('fin_categorias');

        /*
        * Tabela de fornecedores
        */
        $this->forge->addField([
            'id_fornecedor'         => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'tipo_pessoa'           => ['type' => 'ENUM', 'constraint' => ['F', 'J'], 'default' => 'F', 'comment' => 'F = Física, J = Jurídica'],
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
        $this->forge->addPrimaryKey('id_fornecedor');
        $this->forge->createTable('fin_fornecedores');

        /*
        * Tabela de despesas
        */
        $this->forge->addField([
            'id_despesa'                    => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'despesa'                       => ['type' => 'varchar','constraint' => 150, 'null' => true],
            'vencimento_dt'                 => ['type' => 'date', 'null' => true],
            'valor'                         => ['type' => 'float', 'null' => true],
            'categoria'                     => ['type' => 'int', 'constraint' => 11,'null' => true],
            'fornecedor'                    => ['type' => 'int', 'constraint' => 11,'null' => true],
            'comentario'                    => ['type' => 'text', 'null' => true],
            'rateio'                        => ['type' => 'json', 'null' => true],
            'created_at'                    => ['type' => 'datetime', 'null' => true],
            'updated_at'                    => ['type' => 'datetime', 'null' => true],
            'deleted_at'                    => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_despesa');
        $this->forge->createTable('fin_despesas');

        /*
        * Tabela de receitas
        */
        $this->forge->addField([
            'id_receita'                    => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'receita'                       => ['type' => 'varchar','constraint' => 150, 'null' => true],
            'vencimento_dt'                 => ['type' => 'date', 'null' => true],
            'valor'                         => ['type' => 'float', 'null' => true],
            'categoria'                     => ['type' => 'int', 'constraint' => 11,'null' => true],
            'cliente_id'                    => ['type' => 'int', 'constraint' => 11,'null' => true],
            'comentario'                    => ['type' => 'text', 'null' => true],
            'rateio'                        => ['type' => 'json', 'null' => true],
            'created_at'                    => ['type' => 'datetime', 'null' => true],
            'updated_at'                    => ['type' => 'datetime', 'null' => true],
            'deleted_at'                    => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_receita');
        $this->forge->createTable('fin_receitas');

        /*
        * Tabela de pagamentos de despesas
        */
        $this->forge->addField([
            'id_pgto_despesa'               => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'pagamento_despesa_dt'          => ['type' => 'date', 'null' => true],
            'valor'                         => ['type' => 'float', 'null' => true],
            'despesa_id'                    => ['type' => 'int', 'constraint' => 11,'null' => true],
            'conta_id'                      => ['type' => 'int', 'constraint' => 11,'null' => true],
            'comentario'                    => ['type' => 'text', 'null' => true],
            'rateio'                        => ['type' => 'json', 'null' => true],
            'created_at'                    => ['type' => 'datetime', 'null' => true],
            'updated_at'                    => ['type' => 'datetime', 'null' => true],
            'deleted_at'                    => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_pgto_despesa');
        $this->forge->createTable('fin_pgto_despesas');

        /*
        * Tabela de pagamentos de receitas
        */
        $this->forge->addField([
            'id_pgto_receita'               => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'pagamento_receita_dt'          => ['type' => 'date', 'null' => true],
            'valor'                         => ['type' => 'float', 'null' => true],
            'receita_id'                    => ['type' => 'int', 'constraint' => 11,'null' => true],
            'conta_id'                      => ['type' => 'int', 'constraint' => 11,'null' => true],
            'comentario'                    => ['type' => 'text', 'null' => true],
            'rateio'                        => ['type' => 'json', 'null' => true],
            'created_at'                    => ['type' => 'datetime', 'null' => true],
            'updated_at'                    => ['type' => 'datetime', 'null' => true],
            'deleted_at'                    => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_pgto_receita');
        $this->forge->createTable('fin_pgto_receitas');
    }

    public function down()
    {
        $this->forge->dropTable('fin_contas');
        $this->forge->dropTable('fin_categorias');
        $this->forge->dropTable('fin_fornecedores');
        $this->forge->dropTable('fin_despesas');
        $this->forge->dropTable('fin_receitas');
        $this->forge->dropTable('fin_pgto_despesas');
        $this->forge->dropTable('fin_pgto_receitas');
    }
}
