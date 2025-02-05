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

    /** 
     * Retorna as partes de um processo
     */
    public function getParteProcesso(int $id_processo, string $polo){
        $parte = $this->db->table('processos_partes_dos_processos as pdp')
        ->join('processos_partes as pp', 'pdp.id_parte = pp.id_parte', 'left') 
        ->where('id_processo', $id_processo)
        ->where('polo', $polo)
        ->get()->getResultArray();
        return $parte;
    }

    /** 
     * Retorna os processos de uma parte
     * Pesquisando pelo $nome da parte
     * @param string $nome Nome da parte
     * @return array Processos IDs
     */
    public function getParteProcessoPorNome(string $nome){
        $parte = $this->db->table('processos_partes as pp')
        ->join('processos_partes_dos_processos as pdp', 'pp.id_parte = pdp.id_parte', 'left')
        ->join('processos as p', 'pdp.id_processo = p.id_processo', 'left')
        ->like('LOWER(pp.nome)', strtolower(trim($nome)))
        ->select('p.id_processo') // Select only the process IDs
        ->distinct() // Ensure unique process IDs (optional, but recommended)
        ->get()->getResultArray();

        // Extract the process IDs from the result array
        $processosIds = array_column($parte, 'id_processo');
    
        return $processosIds;
    }

    public function getParteProcessoPorId(int $id){
        $parte = $this->db->table('processos_partes as pp')
        ->join('processos_partes_dos_processos as pdp', 'pp.id_parte = pdp.id_parte', 'left')
        ->join('processos as p', 'pdp.id_processo = p.id_processo', 'left')
        ->like('pp.id_parte', $id)
        ->get()->getResultArray();
        return $parte;
    }

    /*
    * Verifica se já existe uma parte no processo
    */
    public function jaExisteParteProcesso(int $id_processo){
        $parte = $this->db->table('processos_partes_dos_processos as pdp')
        ->where('id_processo', $id_processo)
        ->get()->getResultArray();
        return $parte;
    }

    /*
    * Salva uma parte do processo
    */
    public function salvarParteDoProcesso(array $parteDoProcesso){
        $this->db->table('processos_partes_dos_processos')->insert($parteDoProcesso);
    }

    /*
    * Deleta uma parte do processo
    */
    public function deletarParteDoProcesso(int $idProcesso){
        $this->db->table('processos_partes_dos_processos')->delete("id_processo = $idProcesso");
    }

}
