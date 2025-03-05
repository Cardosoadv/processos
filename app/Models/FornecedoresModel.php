<?php

namespace App\Models;

use CodeIgniter\Model;

class FornecedoresModel extends Model
{
    protected $table            = 'fin_fornecedores';
    protected $primaryKey       = 'id_fornecedor';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
            'tipo_pessoa',
            'nome',
            'documento',
            'email',
            'telefone',
            'endereco',
            'complemento',
            'cep',
            'cidade',
            'uf',
            'razao_social',
            'ativo',
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
    protected $beforeInsert   = ['beforeInsert'];
    protected $afterInsert    = ['afterInsert'];
    protected $beforeUpdate   = ['beforeUpdate'];
    protected $afterUpdate    = ['afterUpdate'];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function beforeInsert(array $data)
    {
        log_message('info', 'FornecedoresModel::beforeInsert - Dados: ' . json_encode($data['data']));
        return $data;
    }

    protected function afterInsert(array $data)
    {
        log_message('info', 'FornecedoresModel::afterInsert - Resultado: ' . json_encode($data));
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        log_message('info', 'FornecedoresModel::beforeUpdate - Dados: ' . json_encode($data['data']));
        return $data;
    }

    protected function afterUpdate(array $data)
    {
        log_message('info', 'FornecedoresModel::afterUpdate - Resultado: ' . json_encode($data));
        return $data;
    }


}
