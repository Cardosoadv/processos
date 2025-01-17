<?php

namespace App\Models;

use CodeIgniter\Model;

class ProcessosPartesModel extends Model
{
    protected $table            = 'processos_partes';
    protected $primaryKey       = 'id_parte';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_parte',
        'nome',
        'cliente'    
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

    public function getParteProcesso(int $id_processo, string $polo){
        $parte = $this->db->table('processos_partes_dos_processos as pdp')
        ->join('processos_partes as pp', 'pdp.id_parte = pp.id_parte', 'left') 
        ->where('id_processo', $id_processo)
        ->where('polo', $polo)
        ->get()->getResultArray();
        return $parte;
    }

    public function salvarParteDoProcesso(array $parteDoProcesso){
        $this->db->table('processos_partes_dos_processos')->insert($parteDoProcesso);
    }
    public function deletarParteDoProcesso(int $idProcesso){
        $this->db->table('processos_partes_dos_processos')->delete("id_processo = $idProcesso");
    }

}
