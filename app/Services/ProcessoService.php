<?php

namespace App\Services;

use App\Repositories\ProcessosRepository;

class ProcessoService
{
    protected $processosRepository;

    public function __construct()
    {
        $this->processosRepository = new ProcessosRepository();
    }

    /**
     * Lista os processos.
     * Conforme filtros predefinidos
     * Retorna um array com os processos paginados.
     */
    public function listarProcessos(?string $search, string $sortField, string $sortOrder, ?int $encerrado, ?int $etiqueta = null, int $perPage = 25)
    {
        return $this->processosRepository->buscarProcessos($search, $sortField, $sortOrder, $encerrado, $etiqueta, $perPage);
    }

    /**
     * Lista os processos de um cliente.
     * Utilizando os mesmos filtros da pesquisa geral de processos.
     */
    public function listarProcessosCliente(int $clienteId, ?string $search, string $sortField = 'id_processo', string $sortOrder = 'DESC', ?int $encerrado = null, ?int $etiqueta = null, int $perPage = 25)
    {
        return $this->processosRepository->buscarProcessosCliente($clienteId, $search, $sortField, $sortOrder, $encerrado, $etiqueta, $perPage);
    }

    /**
     * Lista os processos movimentados nos últimos dias.
     * @param string $dias Quantidade de dias para buscar os processos movimentados.
     * @return array Lista de processos movimentados.
     */
    public function getProcessosMovimentados(string $dias): array
    {
        $hoje = date('Y-m-d', time());
        $dataInicial = date('Y-m-d', strtotime('-' . $dias . ' days'));
        return $this->processosRepository->buscarProcessosMovimentados($dataInicial, $hoje);
    }

    /**
     * Retorna os detalhes de um processo
     * @param int $id ID do processo
     * @return array Detalhes do processo
     */
    public function getDetalhesProcesso(int $id): array
    {
        return $this->processosRepository->buscarDetalhesProcesso($id);
    }

    /**
     * Salva um processo.
     *
     * @param array $data Os dados do processo.
     * @param int|null $id O ID do processo (null para novo processo).
     * @return int O ID do processo.
     * @throws \Exception Se ocorrer um erro durante a operação.
     */
    public function salvarProcesso(array $data, ?int $id): int
    {
        return $this->processosRepository->salvarProcesso($data, $id);
    }

    /**
     * Salva uma parte do processo
     */
    public function salvarPartes(array $parte, int $idProcesso): void
    {
        $this->processosRepository->salvarParte($parte, $idProcesso);
    }

    /**
     * Salva uma anotação no processo
     */
    public function salvarAnotacao(array $data): void
    {
        $this->processosRepository->salvarAnotacao($data);
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
        return $this->processosRepository->gerenciarEtiqueta($processoId, $etiquetaId, $adicionar);
    }

    /** 
     * Deleta um processo e seus registros relacionados
     */
    public function deletarProcesso(int $id): bool
    {
        return $this->processosRepository->deletarProcesso($id);
    }

    /**
     * Salva um movimento do processo
     */
    public function salvarMovimento(array $data): void
    {
        $this->processosRepository->salvarMovimento($data);
    }

    /**
     * Verifica se um processo já existe
     */
    public function processoJaExiste(string $numeroProcesso): ?array
    {
        return $this->processosRepository->verificarProcessoExistente($numeroProcesso);
    }

    /**
     * Salva Vinculo de Processos
     */
    public function salvarVinculo(array $data): void
    {
        $this->processosRepository->salvarVinculo($data);
    }

    /**
     * Excluir Vinculo de Processos
     */
    public function excluirVinculo(int $id): void
    {
        $this->processosRepository->excluirVinculo($id);
    }
}