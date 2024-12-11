<?php

namespace App\Models;

use CodeIgniter\Model;

class IntimacoesModel extends Model
{
    protected $table            = 'intimacoes';
    protected $primaryKey       = 'id_intimacao';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
            'id_intimacao',
            'data_disponibilizacao',
            'tipoComunicacao',
            'texto',
            'numero_processo',
            'meio',
            'link',
            'numeroComunicacao',
            'hash',
            'motivo_cancelamento',
            'data_cancelamento',
            'datadisponibilizacao',
            'dataenvio',
            'meiocompleto',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

   /**
     * Função para verificar se a intimação já consta do db
     * @param string $id
     * @return bool
     */
    public function intimacaoJaExiste(string $id): bool {
        $query = $this->db->table('intimacoes')
                        ->select('id_intimacao')
                        ->where('id_intimacao', $id)
                        ->get();
        return $query->getRowArray() !== null;
    }

    public function getProcessoMovimentadoPeriodo($dataInicial, $dataFinal){
        $dtInicial = date('Y-m-d', strtotime($dataInicial));
        $dtFinal = date('Y-m-d', strtotime($dataFinal));
        $data = $this->where('data_disponibilizacao >=',$dtInicial)
        ->where('data_disponibilizacao <=',$dtFinal)
        ->get()->getResultArray();
        return $data;
    }


}
