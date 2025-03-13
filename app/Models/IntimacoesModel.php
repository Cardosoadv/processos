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

    public function getIntimacoesdoPeriodo($dataInicial, $dataFinal){
        $dtInicial = date('Y-m-d', strtotime($dataInicial));
        $dtFinal = date('Y-m-d', strtotime($dataFinal));
        $data = $this->where('data_disponibilizacao >=',$dtInicial)
        ->where('data_disponibilizacao <=',$dtFinal)
        ->orderBy('data_disponibilizacao', 'DESC')
        ->limit(10)
        ->get()->getResultArray();
        return $data;
    }

    public function joinTabelasProcesso(){
        $data = $this->db
        ->table('intimacoes_destinatario as d')
        ->join('intimacoes as i', 'd.comunicacao_id = i.id_intimacao', 'left')
        ->join('processos as p', 'p.numero_processo = i.numero_processo', 'left');
        return $data->get()->getResultArray();
    }


    #---------------------------------------------------------------------------------------------
    #                                           AUDITORIA
    #---------------------------------------------------------------------------------------------

    public function auditoriaNovoProcesso($dados_novos)
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
