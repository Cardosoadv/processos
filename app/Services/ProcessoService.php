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

    public function __construct()
    {
        $this->db                           = db_connect();
        $this->processosModel               = model('ProcessosModel');
        $this->partesProcessoModel          = model('ProcessosPartesModel');
        $this->processosAnotacoesModel      = model('ProcessosAnotacoesModel');
        $this->processosMovimentosModel     = model('ProcessosMovimentosModel');
        $this->intimacoesModel              = model('IntimacoesModel');
        $this->tarefasModel                 = model('TarefasModel');
    }

    public function listarProcessos(?string $search, string $sortField, string $sortOrder, int $perPage = 25, ?int $encerrado = 0)
    {
        if($search === null) {
            return $this->processosModel
                                        ->where('encerrado', 0)
                                        ->orderBy($sortField, $sortOrder)
                                        ->joinProcessoCliente($perPage);
        }

        if($encerrado === null) {
            return $this->processosModel
                                        ->orderBy($sortField, $sortOrder)
                                        ->groupStart()
                                        ->like('numero_processo', $search)
                                        ->orLike('titulo_processo', $search)
                                    ->groupEnd()
                                    ->orderBy($sortField, $sortOrder)
                                    ->joinProcessoCliente($perPage);
        }

        return $this->processosModel
                                    ->where('encerrado', 0)
                                    ->groupStart()
                                        ->like('numero_processo', $search)
                                        ->orLike('titulo_processo', $search)
                                    ->groupEnd()
                                    ->orderBy($sortField, $sortOrder)
                                    ->joinProcessoCliente($perPage);
    }

    public function listarProcessosCliente(int $clienteId, ?string $search, int $perPage = 25)
    {
        if($search === null) {
            return $this->processosModel
                ->where('cliente_id', $clienteId)
                ->joinProcessoCliente($perPage);
        }

        return $this->processosModel
            ->where('cliente_id', $clienteId)
            ->groupStart()
                ->like('numero_processo', $search)
                ->orLike('titulo_processo', $search)
            ->groupEnd()
            ->joinProcessoCliente($perPage);
    }

    public function getProcessosMovimentados(string $dias): array
    {
        $hoje = date('Y-m-d', time());
        $dataInicial = date('Y-m-d', strtotime('-'.$dias.' days'));
        return $this->processosMovimentosModel->getProcessoMovimentadoPeriodo($dataInicial, $hoje);
    }

    public function getDetalhesProcesso(int $id): array
    {
        $processo = $this->processosModel->where('id_processo', $id)->get()->getRowArray();
        $numeroProcesso = $processo['numero_processo'];

        return [
            'processo' => $processo,
            'poloAtivo' => $this->partesProcessoModel->getParteProcesso($id, 'A'),
            'poloPassivo' => $this->partesProcessoModel->getParteProcesso($id, 'P'),
            'anotacoes' => $this->processosAnotacoesModel->getAnotacoesPublicasOuDoUsuarioPorProcesso(user_id(), $id),
            'movimentacoes' => $this->processosMovimentosModel->where('numero_processo', $numeroProcesso)
                ->orderBy('dataHora', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray(),
            'intimacoes' => $this->intimacoesModel->where('numero_processo', $numeroProcesso)
                ->orderBy('data_disponibilizacao', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray(),
            'etiquetas' => $this->processosModel->joinEtiquetasProcessos($id),
            'tarefas' => $this->tarefasModel->where('processo_id', $id)->get()->getResultArray()
        ];
    }

    public function salvarProcesso(array $data, ?int $id): int
    {
        $this->db->transStart();

        try {
            if (!is_numeric($id)) {
                $this->processosModel->insert($data);
                $id = $this->processosModel->insertID();
            } else {
                $this->partesProcessoModel->deletarParteDoProcesso($id);
                $this->processosModel->update($id, $data);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Erro ao salvar processo');
            }

            return $id;
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function salvarPartes(array $parte, int $idProcesso): void
    {
        $jaExisteParte = $this->partesProcessoModel->where('nome', $parte['nome'])->first();
        
        if ($jaExisteParte) {
            $idParteProcesso = $jaExisteParte['id_parte'];
        } else {
            $this->partesProcessoModel->insert(['nome' => $parte['nome']]);
            $idParteProcesso = $this->partesProcessoModel->insertID();
        }

        $this->partesProcessoModel->salvarParteDoProcesso([
            'id_parte' => $idParteProcesso,
            'id_processo' => $idProcesso,
            'polo' => $parte['polo']
        ]);
    }

    public function salvarAnotacao(array $data): void
    {
        $this->processosAnotacoesModel->insert($data);
    }

    public function gerenciarEtiquetas(int $processoId, int $etiquetaId, bool $adicionar): bool
    {
        if ($adicionar) {
            return $this->processosModel->addEtiqueta($processoId, $etiquetaId);
        }
        return $this->processosModel->removeEtiqueta($processoId, $etiquetaId);
    }

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

}
