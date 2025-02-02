<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientesModel extends Model
{
    protected $table            = 'clientes';
    protected $primaryKey       = 'id_cliente';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        
        'tipo_cliente', //'F = Física, J = Jurídica'
        'nome',
        'documento',// 'CPF para física, CNPJ para jurídica'
        'email',
        'telefone',
        'endereco',
        'complemento',
        'cep',
        'cidade',
        'uf',
        'razao_social',
        'ativo', //'1 = Sim, 0 = Não'    
        'dataAquisicao',
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
    protected $afterInsert    = ['auditoriaNovoCliente'];
    protected $beforeUpdate   = ['auditoriaAtualizarCliente'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['auditoriaDeletarCliente'];
    protected $afterDelete    = [];



    public function auditoriaNovoCliente($dados_novos)
    {
        $auditoria = model('AuditoriaClientes');
        $cliente_id = $this->getInsertID();
        $auditoria->insert([
            'cliente_id' => $cliente_id,
            'user_id' => user_id(),
            'action_type' => 'CREATE',
            'dados_novos' => json_encode($dados_novos),
            'ip_address' => service('request')->getIPAddress(),
        ]);
        return $cliente_id;
    }

    public function auditoriaAtualizarCliente($dados_novos)
    {
        $auditoria = model('AuditoriaClientes');
        $cliente_id = $dados_novos['id'];
        $dados_antigos = $this->find($cliente_id);
        $auditoria->insert([
            'cliente_id' => $cliente_id,
            'user_id' => user_id(),
            'action_type' => 'UPDATE',
            'dados_antigos' => json_encode($dados_antigos),
            'dados_novos' => json_encode($dados_novos),
            'ip_address' => service('request')->getIPAddress(),
        ]);
        return $cliente_id;
    }
    public function auditoriaDeletarCliente($dados)
    {
        $auditoria = model('AuditoriaClientes');
        $cliente_id = $dados['id'];
        $dados_antigos = $this->find($cliente_id);
        $auditoria->insert([
            'cliente_id' => $cliente_id,
            'user_id' => user_id(),
            'action_type' => 'DELETE',
            'dados_antigos' => json_encode($dados_antigos),
            'ip_address' => service('request')->getIPAddress(),
        ]);
        return $cliente_id;
    }


}
