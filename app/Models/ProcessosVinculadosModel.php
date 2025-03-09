<?php

namespace App\Models;

use CodeIgniter\Model;

class ProcessosVinculadosModel extends Model
{
    protected $table            = 'processos_vinculados';
    protected $primaryKey       = 'id_vinculo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_vinculo',
        'id_processo_a',
        'id_processo_b',
        'tipo_vinculo' 
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
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


    public function getVinculosProcesso(int $idProcesso){
        $processos = $this->where('id_processo_a', $idProcesso)->orWhere('id_processo_b', $idProcesso)->findAll();
        $vinculados = [];
        foreach ($processos as $processo) {
            if ($processo['id_processo_a'] != $idProcesso) {
                $vinculadoId = $processo['id_processo_a'];
            } else {
                $vinculadoId = $processo['id_processo_b'];
            }
    
            $vinculados[] = [
                'id_vinculo' => $processo['id_vinculo'],
                'numeroprocessocommascara' => model('ProcessosModel')->getNumeroProcessoComMascara($vinculadoId),
                'id_processo_vinculado' => $vinculadoId,
                'tipo_vinculo' => $processo['tipo_vinculo'],
            ];
        }
        return $vinculados;
    }
}
