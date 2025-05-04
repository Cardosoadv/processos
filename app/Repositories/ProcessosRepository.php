<?php

namespace App\Repositories;

use CodeIgniter\Database\BaseConnection;

/**
 * Repository for managing legal processes and their related data
 * 
 * Handles database operations for processes, including searching, creating, 
 * updating, and deleting processes and their associated entities like 
 * parties, annotations, movements, and tags.
 */
class ProcessosRepository
{
    protected $db;
    protected $processosModel;
    protected $partesProcessoModel;
    protected $processosAnotacoesModel;
    protected $processosMovimentosModel;
    protected $intimacoesModel;
    protected $tarefasModel;
    protected $processoObjetoModel;
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
        $this->processoObjetoModel          = model('ProcessoObjetoModel');
        $this->processosVinculadosModel     = model('ProcessosVinculadosModel');
    }

    /**
     * Busca processos com filtros aplicados
     */
    public function buscarProcessos(?string $search, string $sortField, string $sortOrder, ?int $encerrado, ?int $etiqueta = null, int $perPage = 25)
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
                //ou, Pesquisa pela Classe
                ->orLike('LOWER(tipoDocumento)', strtolower(trim($search)), 'both')
                ->orWhereIn('id_processo', $this->partesProcessoModel->getParteProcessoPorNome($search))
                ->groupEnd();
        }

        // Filtro por etiqueta
        if ($etiqueta !== null) {
            $builder->join('processos_etiquetas', 'processos.id_processo = processos_etiquetas.processo_id')
                ->where('processos_etiquetas.etiqueta_id', $etiqueta);
        }

        // Ordena os processos
        $builder->orderBy($sortField, $sortOrder);

        return $builder->joinProcessoCliente($perPage);
    }

    /**
     * Busca processos de um cliente específico com os mesmos filtros da pesquisa geral
     */
    public function buscarProcessosCliente(int $clienteId, ?string $search, string $sortField, string $sortOrder, ?int $encerrado, ?int $etiqueta = null, int $perPage = 25)
    {
        $builder = $this->processosModel->where('cliente_id', $clienteId);

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
            $builder->join('processos_etiquetas', 'processos.id_processo = processos_etiquetas.processo_id')
                ->where('processos_etiquetas.etiqueta_id', $etiqueta);
        }

        // Ordena os processos
        $builder->orderBy($sortField, $sortOrder);

        return $builder->joinProcessoCliente($perPage);
    }

    /**
     * Busca processos de um objeto específico com os mesmos filtros da pesquisa geral
     */
    public function buscarProcessosObjeto(
                                            int $objetoId, 
                                            ?string $search, 
                                            string $sortField, 
                                            string $sortOrder, 
                                            ?int $encerrado, 
                                            ?int $etiqueta = null, 
                                            int $perPage = 25, 
                                            ?int $clienteId = null
                                            )
    {

        $processosIds = $this->processoObjetoModel->getProcessoPorObjeto($objetoId);

        // Verifica se o array de IDs de processos não está vazio
        if (!empty($processosIds)) {
            $builder = $this->processosModel
                                            ->where('cliente_id', $clienteId)
                                            ->whereIn('id_processo', $processosIds);
        } else {
            // Se o array estiver vazio, retorna uma consulta que não retorna nenhum resultado
            $builder = $this->processosModel
                                            ->where('cliente_id', $clienteId)
                                            ->where('id_processo', null);
        }

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
            $builder->join('processos_etiquetas', 'processos.id_processo = processos_etiquetas.processo_id')
                ->where('processos_etiquetas.etiqueta_id', $etiqueta);
        }

        // Ordena os processos
        $builder->orderBy($sortField, $sortOrder);

        return $builder->joinProcessoCliente($perPage);
    }

    public function buscarClienteProcesso(int $id): ?int
    {
        return $this->processosModel->where('id_processo', $id)->get()->getRowArray()['cliente_id'] ?? null;
    }


    /**
     * Busca processos movimentados em um período específico
     */
    public function buscarProcessosMovimentados(string $dataInicial, string $dataFinal): array
    {
        return $this->processosMovimentosModel->getProcessoMovimentadoPeriodo($dataInicial, $dataFinal);
    }

    /**
     * Busca detalhes de um processo específico
     */
    public function buscarDetalhesProcesso(int $id): array
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
            'objetos'           => $this->processoObjetoModel->selecionarObjetoPorProcessoId($id),
        ];
    }

    /**
     * Salva um processo no banco de dados
     */
    public function salvarProcesso(array $data, ?int $id): int
    {
        $this->db->transStart();

        try {
            if ($id === null) {
                $this->processosModel->insert($data);
                $id = $this->processosModel->getInsertID();

                if (!$id) {
                    throw new \Exception('Failed to insert process.');
                }
            } else {
                $this->partesProcessoModel->deletarParteDoProcesso($id);
                $result = $this->processosModel->update($id, $data);

                if (!$result) {
                    throw new \Exception('Failed to update process.');
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Database transaction failed.');
            }

            return $id;
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error saving process: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Salva uma parte de processo
     */
    public function salvarParte(array $parte, int $idProcesso): void
    {
        $jaExisteParte = $this->partesProcessoModel->where('nome', $parte['nome'])->first();

        if ($jaExisteParte) {
            $idParteProcesso = $jaExisteParte['id_parte'];
        } else {
            $this->partesProcessoModel->insert(['nome' => $parte['nome']]);
            $idParteProcesso = $this->partesProcessoModel->insertID();
        }

        $jaExisteParteProcesso = $this->partesProcessoModel->jaExisteParteProcesso($idProcesso);
        if (!$jaExisteParteProcesso) {
            $this->partesProcessoModel->salvarParteDoProcesso([
                'id_parte' => $idParteProcesso,
                'id_processo' => $idProcesso,
                'polo' => $parte['polo']
            ]);
        }
    }

    /**
     * Salva uma anotação de processo
     */
    public function salvarAnotacao(array $data): void
    {
        $this->processosAnotacoesModel->insert($data);
    }

    /**
     * Gerencia etiquetas de um processo
     */
    public function gerenciarEtiqueta(int $processoId, int $etiquetaId, bool $adicionar): bool
    {
        if ($adicionar) {
            return $this->processosModel->addEtiqueta($processoId, $etiquetaId);
        }
        return $this->processosModel->removeEtiqueta($processoId, $etiquetaId);
    }

    /**
     * Deleta um processo e seus relacionamentos
     */
    public function deletarProcesso(int $id): bool
    {
        $this->db->transStart();

        try {
            // Deletar registros relacionados
            $this->partesProcessoModel->deletarParteDoProcesso($id);
            $this->processosAnotacoesModel->where('processo_id', $id)->delete();
            $this->processosModel->removeEtiquetas($id);
            $this->tarefasModel->where('processo_id', $id)->delete();
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
     * Salva um movimento de processo
     */
    public function salvarMovimento(array $data): void
    {
        $this->processosMovimentosModel->insert($data);
    }

    /**
     * Verifica se um processo existe pelo número
     */
    public function verificarProcessoExistente(string $numeroProcesso): ?array
    {
        return $this->processosModel->where('numero_processo', $numeroProcesso)->first();
    }

    /**
     * Salva vínculo entre processos
     */
    public function salvarVinculo(array $data): void
    {
        $this->processosVinculadosModel->insert($data);
    }

    /**
     * Exclui vínculo entre processos
     */
    public function excluirVinculo(int $id): void
    {
        $this->processosVinculadosModel->delete($id);
    }


    /********************* METODOS RELACIONADOS AOS OBJETOS DO PROCESSO *********************/

    public function salvarObjeto(array $dados): int
    {
        if (isset($dados['id_objeto'])) {
            $this->processoObjetoModel->update($dados['id_objeto'], $dados);
            return $dados['id_objeto'];
        } else {
            $this->processoObjetoModel->insert($dados);
            return $this->processoObjetoModel->getInsertID();
        }
    }
    
    public function vincularObjetoProcesso(int $processoId, int $objetoId){
        return $this->processoObjetoModel->vincularProcessoObjeto($processoId, $objetoId);
    }

    public function desvincularObjetoProcesso(int $processoId, int $objetoId){
        return $this->processoObjetoModel->desvincularObjetoProcesso($processoId, $objetoId);
    }

    public function selecionarObjetoPorProcessoId(int $processoId): array
    {
        return $this->processoObjetoModel->selecionarObjetoPorProcessoId($processoId);
    }

    public function obterObjeto(int $id): ?array
    {
        return $this->processoObjetoModel->find($id);
    }

    public function listarObjetos(): array
    {
        return $this->processoObjetoModel->findAll();
    }

    public function deletarObjeto(int $id): void
    {
        $this->processoObjetoModel->delete($id);
    }
}
