<?php

namespace App\Models\Financeiro;

use CodeIgniter\Model;

class FinanceiroTransferenciasModel extends Model
{
    protected $table            = 'fin_transferencias';
    protected $primaryKey       = 'id_transferencia';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transferencia',
        'data_transferencia',
        'id_conta_origem',
        'id_conta_destino',
        'valor',
        'comentarios',
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

    public function getNomeTransferencia($id = null)
    {
        $transferencia = $this->find($id);
        return $transferencia['transferencia'] ?? '';
    }

}
