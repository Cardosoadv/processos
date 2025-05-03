<?php

namespace App\Models;

use CodeIgniter\Model;

class ProcessoObjetoModel extends Model
{
    protected $table            = 'processos_objeto';
    protected $primaryKey       = 'id_objeto';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [

        'dados',
        'cidade',
        'bairro',
        'quadra',
        'lote',
        'inscricao',
        'cod_interno',
        'matricula',
        'cartorio',
        'logradouro',
        'numero',
        'complemento',
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

    
    public function selecionarObjetoPorProcessoId(int $processoId): array
    {
    // Usando a tabela de relação processos_objeto_processo
    $builder = $this->db->table($this->table)
        ->select("{$this->table}.*")
        ->join('processos_objeto_processo', "processos_objeto_processo.objeto_id = {$this->table}.id_objeto")
        ->where('processos_objeto_processo.processo_id', $processoId);
    
    return $builder->get()->getResultArray();
    }

    public function getProcessoPorObjeto($objetoId){
        $builder = $this->db->table('processos_objeto_processo')
            ->select('processo_id')
            ->where('objeto_id', $objetoId);
        return $builder->get()->getRowArray();
    }

    public function vincularProcessoObjeto($processoId, $objetoId){
        $data = [
            'processo_id' => $processoId,
            'objeto_id' => $objetoId
        ];
        $this->db->table('processos_objeto_processo')->insert($data);
    }

    public function desvincularObjetoProcesso($processoId, $objetoId){
        $this->db->table('processos_objeto_processo')
            ->where('processo_id', $processoId)
            ->where('objeto_id', $objetoId)
            ->delete();
    }

    /**
     * Deletes the relationship between a process and an object
     *
     * @param int $processoId The ID of the process
     * @param int $objetoId The ID of the object
     */
    public function deletarProcessoObjeto($processoId, $objetoId){
        $this->db->table('processos_objeto_processo')
            ->where('processo_id', $processoId)
            ->where('objeto_id', $objetoId)
            ->delete();
    }


    
    /*
    public function salvarObjeto(array $dados): int
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
            log_message('error', 'Erro ao salvar objeto: ' . $e->getMessage());
            return 0;
        }
    }
    */

    public function obterObjeto(int $id): ?array
    {
        $objeto = $this->find($id);
        if ($objeto && $objeto['dados']) {
            $objeto['dados'] = json_decode($objeto['dados'], true);
        }
        return $objeto;
    }

    public function listarObjetos(): array
    {
        $objetos = $this->findAll();
        foreach ($objetos as &$objeto) {
            if ($objeto && $objeto['dados']) {
                $objeto['dados'] = json_decode($objeto['dados'], true);
            }
        }
        return $objetos;
    }

    public function listarObjetoProcesso(int $processoId): array
    {

        $query = $this->db->query("SELECT * FROM {$this->table} WHERE JSON_EXTRACT(dados, '$.processo_id') = ?", [(string)$processoId])->getResultArray();
        $dados = [];
        foreach ($query as $dado) {
            if ($dado && $dado['dados']) {

                $item = json_decode($dado['dados'], true);
                $item['id_objeto'] = $dado['id_objeto'];
                array_push($dados, $item);
            }
        }
        return $dados;
    }

    public function deletarObjeto(int $id): bool
    {
        return $this->delete($id);
    }
}
