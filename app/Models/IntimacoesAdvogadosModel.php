<?php

namespace App\Models;

use CodeIgniter\Model;

class IntimacoesAdvogadosModel extends Model
{
    protected $table            = 'intimacoes_advogados';
    protected $primaryKey       = 'id_pk';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pk',    
        'id',
        'comunicacao_id',
        'advogado_id',    
        'advogado_nome',
        'advogado_oab',
        'advogado_oab_uf',
        'created_at',
        'updated_at',
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
    protected $afterInsert    = ['auditoriaNovo'];
    protected $beforeUpdate   = ['auditoriaAtualizar'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['auditoriaDeletar'];
    protected $afterDelete    = [];

    protected $auditoriaModel;
    protected $ip;
    
    public function __construct(){
        parent::__construct();
        $this->auditoriaModel = new AuditoriaModel();
        $this->ip = service('request')->getIPAddress();

    }

    protected function auditoriaNovo($data)
    {
        $data['id'] = $this->getInsertID();
        $this->auditoriaModel->insert([
            'user_id' => user_id(),
            'table_name' => $this->table,
            'action_type' => 'CREATE',
            'dados_novos' => json_encode($data),
            'ip_address' => $this->ip,
        ]);
        return $data;
    }

    protected function auditoriaAtualizar($data)
    {
        $dados_antigos = $this->find($data['id']);
        $this->auditoriaModel->insert([
            'user_id' => user_id(),
            'table_name' => $this->table,
            'action_type' => 'UPDATE',
            'dados_antigos' => json_encode($dados_antigos),
            'dados_novos' => json_encode($data['data']),
            'ip_address' => $this->ip,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return $data;
    }

    protected function auditoriaDeletar($data)
    {

        $dados_antigos = $this->find($data['id']);
        $this->auditoriaModel->insert([
            'user_id' => user_id(),
            'table_name' => $this->table,
            'action_type' => 'DELETE',
            'dados_antigos' => json_encode($dados_antigos),
            'ip_address' => $this->ip,
        ]);
        return $data;
    }
}
