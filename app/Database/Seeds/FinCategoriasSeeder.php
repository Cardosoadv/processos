<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FinCategoriasSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            'Receita Operacional',
            'Salário Rodrigo',
            'Honorário Contrato Avulso',
            'Honorário Sucumbência',
            'Honorário Partido Mensal',
            'Outras receitas operacionais',
            'Receita de Capital',
            'Rendimento Aplicações',
            'Outras Receitas de Capital',
            'Despesa Operacional',
            'Custa Judicial',
            'Contabilidade',
            'Softwares',
            'Outras despesas operacionais',
            'Aquisição de Clientes',
            'Comissão',
            'Marketing',
            'Despesa não Operacional',
            'Outras despesas não operacionais',
            'Impostos e Taxas',
            'INSS',
            'SIMPLES',
            'OAB',
            'Taxas Municipais',
            'Taxas Estaduais',
            'Outras Taxas',
            'Transferências',
            'Aplicação',
            'Resgate',
            'Antecipação de Lucro',
            'Valores de Terceiros',
            'Saldo inicial',
        ];

        $data = [];
        foreach ($categorias as $categoria) {
            $data[] = ['categoria' => $categoria];
        }

        // Usando o modelo para inserir os dados
        $builder = $this->db->table('fin_categorias'); // Nome da sua tabela

        $builder->insertBatch($data);
    }
}