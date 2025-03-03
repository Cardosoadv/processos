<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

class ProcessoService
{
    protected $db;
    protected $processosModel;
    protected $partesProcessoModel;
    protected $processosAnotacoesModel;
    protected $processosMovimentosModel;
    protected $intimacoesModel;
    protected $tarefasModel;
    protected $processosObjetoModel;
    protected $processosVinculadosModel;

    public function __construct()
    {
        $this->db                           = db_connect();
        $this->processosModel               = model('ProcessosModel');
        $this->partesProcessoModel          = model('ProcessosPartesModel');
        $this->processosAnotacoesModel      = model('ProcessosAnotacoesModel');
        $this->processosMovimentosModel     = model('ProcessosMovimentosModel');
        $this->intimacoesModel              = model('IntimacoesModel');
        $this->tarefasModel                 = model('TarefasModel');
        $this->processosObjetoModel         = model('ProcessoObjetoModel');
        $this->processosVinculadosModel    = model('ProcessosVinculadosModel');
    }

    /**
     * Lista os processos.
     * Conforme filtros predefinidos
     * Retorna um array com os processos paginados.
     */
    public function listarProcessos(?string $search, string $sortField, string $sortOrder, ?int $encerrado, ?int $etiqueta = null, int $perPage = 25)
    {
        $builder = $this->processosModel;

        // Filtro por encerrado
        if ($encerrado !== null) {
            $builder->where('encerrado', $encerrado);
        }

        // Filtro por busca (numero_processo ou titulo_processo)
        if ($search !== null) {

            $builder->groupStart()
                //Pesquisa pelo numero do processo sem ponto ou traço
                ->like('numero_processo', preg_replace('/[.-]/', '', $search))
                //Ou, Pesquisa pelo titulo do processo
                ->orLike('LOWER(titulo_processo)', strtolower(trim($search)), 'both')
                //Ou, Pesquisa pelo nome da parte
                ->orWhereIn('id_processo', $this->partesProcessoModel->getParteProcessoPorNome($search))
                ->groupEnd();
        }

        // Filtro por etiqueta
        if ($etiqueta !== null) {
            // Filtra pela etiqueta
            // Este filtro é cumulativo com o anterior
            $builder->join('processos_etiquetas', 'processos.id_processo = processos_etiquetas.processo_id')
                ->where('processos_etiquetas.etiqueta_id', $etiqueta);
        }
        // Ordena os processos
        $builder->orderBy($sortField, $sortOrder);

        return $builder->joinProcessoCliente($perPage);
    }

    /**
     * Lista os processos de um cliente.
     * TODO: Implementar filtros de busca e ordenação.
     */
    public function listarProcessosCliente(int $clienteId, ?string $search, int $perPage = 25)
    {
        if ($search === null) {
            return $this->processosModel
                ->where('cliente_id', $clienteId)
                ->joinProcessoCliente($perPage);
        }

        return $this->processosModel
            ->where('cliente_id', $clienteId)
            ->groupStart()
            ->like('numero_processo', preg_replace('/[.-]/', '', $search))
            ->orLike('titulo_processo', $search)
            ->groupEnd()
            ->joinProcessoCliente($perPage);
    }

    /**
     *  Lista os processos movimentados nos últimos dias.
     * @param string $dias Quantidade de dias para buscar os processos movimentados.
     * @return array Lista de processos movimentados.
     */
    public function getProcessosMovimentados(string $dias): array
    {
        $hoje = date('Y-m-d', time());
        $dataInicial = date('Y-m-d', strtotime('-' . $dias . ' days'));
        return $this->processosMovimentosModel->getProcessoMovimentadoPeriodo($dataInicial, $hoje);
    }

    /*
    * Retorna os detalhes de um processo
    * @param int $id ID do processo
    * @return array Detalhes do processo
    */
    public function getDetalhesProcesso(int $id): array
    {
        $processo = $this->processosModel->where('id_processo', $id)->get()->getRowArray();
        $numeroProcesso = $processo['numero_processo'];

        return [
            'processo'          => $processo,
            'poloAtivo'         => $this->partesProcessoModel->getParteProcesso($id, 'A'),
            'poloPassivo'       => $this->partesProcessoModel->getParteProcesso($id, 'P'),
            'anotacoes'         => $this->processosAnotacoesModel->getAnotacoesPublicasOuDoUsuarioPorProcesso(user_id(), $id),
            'movimentacoes'     => $this->processosMovimentosModel->where('numero_processo', $numeroProcesso)
                ->orderBy('dataHora', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray(),
            'intimacoes'        => $this->intimacoesModel->where('numero_processo', $numeroProcesso)
                ->orderBy('data_disponibilizacao', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray(),
            'etiquetas'         => $this->processosModel->joinEtiquetasProcessos($id),
            'tarefas'           => $this->tarefasModel->where('processo_id', $id)->get()->getResultArray(),
            'vinculos'          => $this->processosVinculadosModel->getVinculosProcesso($id),
            'objetos'           => $this->processosObjetoModel->listarObjetoProcesso($id),
        ];
    }

    /**
     * Saves a process.
     *
     * @param array $data The process data.
     * @param int|null $id The process ID (null for new process).
     * @return int The process ID.
     * @throws \Exception If an error occurs during the database operation.
     */
    public function salvarProcesso(array $data, ?int $id): int
    {

        $this->db->transStart();

        try {
            if ($id === null) { // Use strict comparison
                $this->processosModel->insert($data);
                $id = $this->processosModel->getInsertID();

                if (!$id) { // Check if insert was successful
                    throw new \Exception('Failed to insert process.');
                }
            } else {
                $this->partesProcessoModel->deletarParteDoProcesso($id);
                $result = $this->processosModel->update($id, $data);
                

                if (!$result) { // Check if update was successful
                    throw new \Exception('Failed to update process.');
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Database transaction failed.');
            }

            return $id;

        } catch (\InvalidArgumentException $e) {
            $this->db->transRollback();
            throw $e; // Re-throw the validation exception

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error saving process: ' . $e->getMessage()); // Log the error with more detail
            throw $e; // Re-throw the exception
        }
    }

    /*
    * Salva uma parte do processo
    */
    public function salvarPartes(array $parte, int $idProcesso): void
    {
        $jaExisteParte = $this->partesProcessoModel->where('nome', $parte['nome'])->first();

        if ($jaExisteParte) {
            $idParteProcesso = $jaExisteParte['id_parte'];
        } else {
            $this->partesProcessoModel->insert(['nome' => $parte['nome']]);
            $idParteProcesso = $this->partesProcessoModel->insertID();
        }

        $jaExisteParteProcesso = $this->partesProcessoModel->jaExisteParteProcesso($idProcesso);
        if ($jaExisteParteProcesso) {
            return;
        } else {
            $this->partesProcessoModel->salvarParteDoProcesso([
                'id_parte' => $idParteProcesso,
                'id_processo' => $idProcesso,
                'polo' => $parte['polo']
            ]);
        }
    }

    /**
     * Salva uma anotação no processo
     */
    public function salvarAnotacao(array $data): void
    {
        $this->processosAnotacoesModel->insert($data);
    }

    /**
     * Adiciona ou remove uma etiqueta de um processo
     * @param int $processoId ID do processo
     * @param int $etiquetaId ID da etiqueta
     * @param bool $adicionar Se true, adiciona a etiqueta. Se false, remove a etiqueta.
     * @return bool True se a operação foi bem sucedida, false caso contrário.
     */
    public function gerenciarEtiquetas(int $processoId, int $etiquetaId, bool $adicionar): bool
    {
        if ($adicionar) {
            return $this->processosModel->addEtiqueta($processoId, $etiquetaId);
        }
        return $this->processosModel->removeEtiqueta($processoId, $etiquetaId);
    }

    /*
    * Deleta um processo e seus registros relacionados
    */
    public function deletarProcesso(int $id): bool
    {
        $this->db->transStart();

        try {
            // Deletar registros relacionados
            $this->partesProcessoModel->deletarParteDoProcesso($id);

            // Deletar anotações
            $this->processosAnotacoesModel->where('processo_id', $id)->delete();

            // Deletar etiquetas do processo
            $this->processosModel->removeEtiquetas($id);

            // Deletar tarefas
            $this->tarefasModel->where('processo_id', $id)->delete();

            // Por fim, deletar o processo
            $this->processosModel->delete($id);

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Erro ao deletar processo');
            }

            return true;
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Salva um movimento do processo
     */
    public function salvarMovimento(array $data): void
    {
        $this->processosMovimentosModel->insert($data);
    }

    /*
    * Verifica se um processo já existe
    */
    public function processoJaExiste(string $numeroProcesso): ?array
    {
        return $this->processosModel->where('numero_processo', $numeroProcesso)->first();
    }

    /**
     * Salva Vinculo de Processos
     */
    public function salvarVinculo(array $data): void
    {
        $this->processosVinculadosModel->insert($data);
    }
    /**
     * Excluir Vinculo de Processos
     */
    public function excluirVinculo(int $id): void
    {
        $this->processosVinculadosModel->delete($id);
    }
}
