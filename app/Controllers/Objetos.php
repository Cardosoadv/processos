<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ExportarService;
use Exception;

class Objetos extends BaseController
{
    private $objetoModel;
    public function __construct()
    {
        $this->objetoModel = model('ProcessoObjetoModel');
    }
    
    
    public function index()
    {
        $data['titulo']     = 'Objetos';
        $data['s']          = $this->request->getGet('s');

        if($data['s']){
            $data['objetos'] = $this    ->objetoModel
            
                                        ->like('LOWER(bairro)', strtolower(trim($data['s'])))
                                        ->orLike('LOWER(cidade)', strtolower(trim($data['s'])))
                                        ->orLike('LOWER(cod_interno)', strtolower(trim($data['s'])))
                                        ->orLike('LOWER(logradouro)', strtolower(trim($data['s'])))
                                        ->paginate(25);
        }else{
            $data['objetos'] = $this->objetoModel
                                                    ->orderBy('cidade', 'ASC')
                                                    ->orderBy('bairro', 'ASC')
                                                    ->paginate(25);
        }

        $data['pager']      = $this->objetoModel->pager;

        return $this->loadView('objetos/objetos', $data);
    }

    public function novo()
    {
        $data['titulo'] = 'Novo Objeto';
        return $this->loadView('objetos/consultarObjetos', $data);
    }

    public function editar($id){
        $data['titulo'] = 'Editar Objeto';
        $data['objeto'] = $this->objetoModel->find($id);
        return $this->loadView('objetos/consultarObjetos', $data);
    }

    public function salvar(){
        $id = $this->request->getPost('id_objeto');
        $data = $this->request->getPost();

        if ($id) {
            try{
            $this->objetoModel->update($id, $data);
            return redirect()->to(base_url('objetos'))->with('success', 'Objeto atualizado com sucesso!');
            }
            catch(Exception $e){
                return redirect()->back()->with('error', 'Erro ao atualizar o objeto: ' . $e->getMessage());
            }
        } else {
            $this->objetoModel->insert($data);
        }
    }

    public function excluir($id)
    {
        $this->objetoModel->delete($id);
        return redirect()->to(base_url('objetos'));
    }


    public function exportar()
    {
        $data['titulo'] = 'Exportar Objetos';
        $data['objetos'] = $this->objetoModel
                                                ->orderBy('cidade', 'ASC')
                                                ->orderBy('bairro', 'ASC')
                                                ->join('processos_objeto_processo as pop', 'pop.objeto_id = processos_objeto.id_objeto', 'left')
                                                ->join('processos as p', 'p.id_processo = pop.processo_id', 'left')
                                                ->get()->getResultArray();
        
        $cabecalho = array_keys($data['objetos'][0]);

        // Se o usuário escolheu exportar para CSV
        if ($this->request->getGet('format') === 'csv') {
            return $this->exportarParaCSV($data['objetos'], 'objetos_' . date('Y-m-d'), $cabecalho);
        }



         // Caso contrário, exibir a tabela na interface
        return $this->loadview('objetos/exportar', $data);
    }

    /**
     * Exporta um array de dados para um arquivo CSV
     *
     * @param array $data Array de dados a serem exportados
     * @param string $filename Nome do arquivo a ser gerado (sem extensão)
     * @param array $headers Cabeçalhos do CSV
     * @return void
     */
    public function exportarParaCSV(array $data, string $filename = 'export', array $headers = [])
    {
        // Adicionar extensão .csv se não estiver presente
        if (!str_ends_with($filename, '.csv')) {
            $filename .= '.csv';
        }
        
        // Configurar cabeçalhos HTTP para download
        $response = service('response');
        $response->setHeader('Content-Type', 'text/csv');
        $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', '0');
        
        // Abrir o output como um stream PHP
        $output = fopen('php://output', 'w');
        
        // Escrever o BOM (Byte Order Mark) para UTF-8
        fwrite($output, "\xEF\xBB\xBF");
        
        // Escrever cabeçalhos
        fputcsv($output, $headers);
        
        // Escrever os dados
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        // Fechar o stream
        fclose($output);
        
        // Encerrar o script para evitar que qualquer outro conteúdo seja enviado
        exit;
    }

}
