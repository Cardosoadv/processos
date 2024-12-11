<?php

namespace App\Models;

use CodeIgniter\Model;

class ProcessosModel extends Model
{
    protected $table            = 'processos';
    protected $primaryKey       = 'id_processo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_processo',
        'siglaTribunal',
        'nomeOrgao',
        'numero_processo',
        'link',
        'tipoDocumento',
        'codigoClasse',
        'ativo',
        'status',
        'numeroprocessocommascara',
        'risco',
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

    public function getProcesso(string $parte){
        $query = $this->db->table('intimacoes_destinatario as d')
        ->join('intimacoes as i', 'd.comunicacao_id = i.id_intimacao', 'left')
        ->join('processos as p', 'p.numero_processo = i.numero_processo', 'left')
        ->select('p.numero_processo')
        ->where('d.nome', $parte)
        ->distinct()
        ->get();
        return $query->getResultArray();
    }

}
