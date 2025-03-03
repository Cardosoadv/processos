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
        'titulo_processo',
        'link',
        'tipoDocumento',
        'codigoClasse',
        'ativo',
        'status', //P
        'numeroprocessocommascara',
        'risco', //'Provável', 'Possível', 'Remoto'
        'dataDistribuicao',
        'valorCausa',
        'resultado', //'Não Finalizado', 'Sucesso', 'Sucesso Parcial', 'Derrota'
        'valorCondenacao',
        'comentario',
        'cliente_id',
        'dataRevisao',
        'encerrado',
        'data_encerramento',
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
    protected $allowCallbacks = false;
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

    public function getNumeroProcessoComMascara($id) : string
    {
        $query = $this->db->table('processos')
        ->select('numeroprocessocommascara')
        ->where('id_processo', $id)
        ->get();

    $row = $query->getRowArray(); // Obtém a primeira linha como um array associativo

    if ($row) {
        return $row['numeroprocessocommascara'];
    } else {
        return ''; // Retorna uma string vazia se o processo não for encontrado
    }
    }
    
    public function joinEtiquetasProcessos($id_processo){
        $query = $this->db->table('processos_etiquetas as pe')
        ->join('etiquetas as e', 'pe.etiqueta_id = e.id_etiqueta', 'left')
        ->select('e.id_etiqueta, e.nome, e.cor, pe.processo_id')
        ->where('pe.processo_id', $id_processo)
        ->get();
        return $query->getResultArray();
    }


    /**
     * Remove a etiqueta de um processo
     * @param int $id_processo
     * @param int $id_etiqueta
     * 
     */
    public function addEtiqueta($id_processo, $id_etiqueta){
        $query = $this->db->table('processos_etiquetas')
        ->insert([
            'processo_id' => $id_processo,
            'etiqueta_id' => $id_etiqueta
        ]);
        return $this->db->affectedRows();
    }


    /**
     * Remove a etiqueta de um processo
     * @param int $id_processo
     * @param int $id_etiqueta
     * @return int Número de linhas afetadas
     */
    public function removeEtiqueta($id_processo, $id_etiqueta){
        $query = $this->db->table('processos_etiquetas')
        ->where('processo_id', $id_processo)
        ->where('etiqueta_id', $id_etiqueta)
        ->delete();
        return $this->db->affectedRows();
    }

    public function removeEtiquetas($id_processo){
        $query = $this->db->table('processos_etiquetas')
        ->where('processo_id', $id_processo)
        ->delete();
        return $this->db->affectedRows();
    }

    /**
     * Join processo e cliente
     */
    public function joinProcessoCliente(?int $porPagina = 25){
        $this->builder()
        ->join('clientes', 'processos.cliente_id = clientes.id_cliente', 'left');
        
        return [
            'processos'  => $this->paginate($porPagina),
            'pager' => $this->pager,
        ];
    }

    
    public function auditoriaNovoProcesso($dados_novos)
    {
        $auditoria = model('AuditoriaProcessos');
        $processo_id = $this->getInsertID();
        $auditoria->insert([
            'processo_id' => $processo_id,
            'user_id' => user_id(),
            'action_type' => 'CREATE',
            'dados_novos' => json_encode($dados_novos),
            'ip_address' => service('request')->getIPAddress(),
        ]);
        return $processo_id;
    }

    public function auditoriaAtualizarProcesso($dados_novos)
    {
        $auditoria = model('AuditoriaProcessos');
        $processo_id = $dados_novos['id'];
        $dados_antigos = $this->find($processo_id);
        $auditoria->insert([
            'processo_id' => $processo_id,
            'user_id' => user_id(),
            'action_type' => 'UPDATE',
            'dados_antigos' => json_encode($dados_antigos),
            'dados_novos' => json_encode($dados_novos),
            'ip_address' => service('request')->getIPAddress(),
        ]);
    }

    public function getResultUpdate($data){
        return $data;
    }


    public function auditoriaDeletarProcesso($dados)
    {
        $auditoria = model('AuditoriaProcessos');
        $processo_id = $dados['id'];
        $dados_antigos = $this->find($processo_id);
        $auditoria->insert([
            'cliente_id' => $processo_id,
            'user_id' => user_id(),
            'action_type' => 'DELETE',
            'dados_antigos' => json_encode($dados_antigos),
            'ip_address' => service('request')->getIPAddress(),
        ]);
        return $processo_id;
    }

}
