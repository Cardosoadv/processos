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
    protected $afterInsert    = ['auditoriaNovo'];
    protected $beforeUpdate   = ['auditoriaAtualizar'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['auditoriaDeletar'];
    protected $afterDelete    = [];

    protected $auditoriaModel;
    protected $ip;

    public function __construct()
    {
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

    /** 
     * Retorna as partes de um processo
     */
    public function getParteProcesso(int $id_processo, string $polo)
    {
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
    public function getParteProcessoPorNome(string $nome)
    {
        $result = $this->db->table('processos_partes as pp')
            ->select('pdp.id_processo')
            ->join('processos_partes_dos_processos as pdp', 'pp.id_parte = pdp.id_parte')
            ->like('LOWER(pp.nome)', strtolower(trim($nome)))
            ->get()
            ->getResultArray();

        if (empty($result)) {
            return [0]; // Retorna array com ID 0
        }

        // Extrai apenas os IDs do array de resultados
        return array_column($result, 'id_processo');
    }

    /** 
     * Retorna os processos de uma parte ativa
     * Pesquisando pelo $nome da parte
     * @param string $nome Nome da parte
     * @return array Processos IDs
     */
    public function getAtivoProcessoPorNome(string $nome)
    {
        $result = $this->db->table('processos_partes as pp')
            ->select('pdp.id_processo')
            ->join('processos_partes_dos_processos as pdp', 'pp.id_parte = pdp.id_parte')
            ->like('LOWER(pp.nome)', strtolower(trim($nome)))
            ->where('polo', 'A') // Adiciona a condição para o campo ativo
            ->get()
            ->getResultArray();

        if (empty($result)) {
            return [0]; // Retorna array com ID 0
        }

        // Extrai apenas os IDs do array de resultados
        return array_column($result, 'id_processo');
    }

    /** 
     * Retorna os processos de uma parte passiva
     * Pesquisando pelo $nome da parte
     * @param string $nome Nome da parte
     * @return array Processos IDs
     */
    public function getPassivoProcessoPorNome(string $nome)
    {
        $result = $this->db->table('processos_partes as pp')
            ->select('pdp.id_processo')
            ->join('processos_partes_dos_processos as pdp', 'pp.id_parte = pdp.id_parte')
            ->like('LOWER(pp.nome)', strtolower(trim($nome)))
            ->where('polo', 'P') // Adiciona a condição para o campo ativo
            ->get()
            ->getResultArray();

        if (empty($result)) {
            return [0]; // Retorna array com ID 0
        }

        // Extrai apenas os IDs do array de resultados
        return array_column($result, 'id_processo');
    }




    public function getParteProcessoPorId(int $id)
    {
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
    public function jaExisteParteProcesso(int $id_processo)
    {
        $parte = $this->db->table('processos_partes_dos_processos as pdp')
            ->where('id_processo', $id_processo)
            ->get()->getResultArray();
        return $parte;
    }

    /*
    * Salva uma parte do processo
    */
    public function salvarParteDoProcesso(array $parteDoProcesso)
    {
        $this->db->table('processos_partes_dos_processos')->insert($parteDoProcesso);
    }

    /*
    * Deleta uma parte do processo
    */
    public function deletarParteDoProcesso(int $idProcesso)
    {
        $this->db->table('processos_partes_dos_processos')->delete("id_processo = $idProcesso");
    }
}
