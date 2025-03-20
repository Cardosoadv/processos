<?php

namespace App\Models;

use CodeIgniter\Model;

class MensagensModel extends Model
{
    protected $table            = 'mensagens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
            'remetente_id',
            'destinatario_id',
            'conteudo',
            'data_envio',
            'data_leitura',
            'assunto',
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

    public function mensagensNaoLidasPorDestinatario($destinatario_id){
        return $this->where('destinatario_id', $destinatario_id)
                    ->where('data_leitura', null)
                    ->findAll();
    }

    public function qteMensagensNaoLidasPorDestinatario($destinatario_id){
        return count($this->mensagensNaoLidasPorDestinatario($destinatario_id));
    }
}
