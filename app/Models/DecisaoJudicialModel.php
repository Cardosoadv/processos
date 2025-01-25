<?php

namespace App\Models;

use CodeIgniter\Model;

class DecisaoJudicialModel extends Model
{
    protected $table = 'decisoes_judiciais';
    protected $primaryKey = 'id_decisao';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $allowedFields = ['dados'];

    public function salvarDecisao(array $dados): int
    {
        try {
            $jsonData = json_encode($dados, JSON_UNESCAPED_UNICODE); // JSON_UNESCAPED_UNICODE para caracteres especiais
            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'Erro ao codificar JSON: ' . json_last_error_msg());
                return 0; // Ou lançar uma exceção, dependendo da sua necessidade
            }
            $this->insert(['dados' => $jsonData]);
            return $this->insertID();
        } catch (\Exception $e) {
            log_message('error', 'Erro ao salvar decisão: ' . $e->getMessage());
            return 0;
        }
    }

    public function obterDecisao(int $id): ?array
    {
       $decisao = $this->find($id);
       if ($decisao && $decisao['dados']) {
           $decisao['dados'] = json_decode($decisao['dados'], true);
       }
       return $decisao;
    }

        public function listarDecisoes(): array
    {
        $decisoes = $this->findAll();
                foreach ($decisoes as &$decisao) {
            if ($decisao && $decisao['dados']) {
                $decisao['dados'] = json_decode($decisao['dados'], true);
            }
        }
        return $decisoes;
    }
}