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
        'risco', //'Provável', 'Possível', 'Remoto'
        'dataDistribuicao',
        'valorCausa',
        'resultado', //'Não Finalizado', 'Sucesso', 'Sucesso Parcial', 'Derrota'
        'valorCondenacao',
        'comentario',
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

}
