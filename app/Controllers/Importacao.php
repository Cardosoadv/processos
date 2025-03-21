<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Importacao extends BaseController
{
    public function __construct()
    {
        helper(['form']);
    }
    
    public function index()
    {
        return $this->loadView('upload_form');
    }

    public function process()
    {
        $validationRule = [
            'userfile' => [
                'uploaded[userfile]',
                'mime_in[userfile,text/csv,text/plain]',
                'ext_in[userfile,csv]',
                'max_size[userfile,2048]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            return $this->loadView('upload_form', ['errors' => $this->validator->listErrors()]);
        }

        $file = $this->request->getFile('userfile');

        if (!$file->isValid() || $file->hasMoved()) {
            return $this->loadView('upload_form', ['errors' => 'Erro ao enviar o arquivo.']);
        }

        try {
            // Move file to uploads directory
            $newName = $file->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads';
            $file->move($uploadPath, $newName);

            // Process the CSV file
            $filepath = $uploadPath . '/' . $newName;
            $csvData = $this->processCSV($filepath);
            $arrayProcessos = $this->formatProcessData($csvData);

            // Clean up - optionally remove the file after processing
            // unlink($filepath);

            // You might want to return a success view instead of print_r
            
            for($i=1;$i<count($arrayProcessos); $i++){
                $this->salvarProcesso($arrayProcessos[$i]);
            }


        } catch (Exception $e) {
            log_message('error', 'Error processing CSV: ' . $e->getMessage());
            return $this->loadView('upload_form', ['errors' => 'Erro ao processar o arquivo: ' . $e->getMessage()]);
        }
    }

    private function salvarProcesso($processo){

        $processosModel = model('ProcessosModel');
        $clienteModel = model('ClientesModel');

        $cliente = $clienteModel->where('nome', 'Construtiva Empreendimentos Ltda')->first();
        $processo['cliente_id'] = $cliente['id_cliente'];

        // Verifica se já existe um processo com o mesmo número.

        $jaExisteProcesso = $processosModel->where('numeroprocessocommascara', $processo['numeroprocessocommascara'])->first();
        if ($jaExisteProcesso) {
            // Processo já existe, retorna o ID existente.
            return $jaExisteProcesso['id_processo'];
        }

        // Usa o método insert() para inserir os dados.
        $processosModel->insert($processo);

        // Retorna o ID do processo inserido.
        $idProcesso = $processosModel->insertID();

        $processoController = new Processos;

        foreach ($processo['poloAtivo'] as $poloativo){
            $parte = [
                'nome' => $poloativo,
                'polo' => 'A'
            ];
            $processoController->salvarPartes($parte, $idProcesso);
        }
        foreach ($processo['poloPassivo'] as $polopassivo){
            $parte = [
                'nome' => $polopassivo,
                'polo' => 'P'
            ];
            $processoController->salvarPartes($parte, $idProcesso);
        }

    }
    
    
    
    
    
    private function processPartes(?string $parte): array
    {
        return $parte ? explode(';', $parte) : [];
    }
    
    
    
    private function processCSV(string $filepath): array
    {
        if (!file_exists($filepath)) {
            throw new Exception('Arquivo não encontrado');
        }

        $file = fopen($filepath, 'r');
        if ($file === false) {
            throw new Exception('Não foi possível abrir o arquivo');
        }

        $csvData = [];
        while (($row = fgetcsv($file, 10000, ";")) !== FALSE) {
            $csvData[] = $row;
        }
        fclose($file);

        if (empty($csvData)) {
            throw new Exception('Arquivo CSV vazio');
        }

        return $csvData;
    }

    private function formatProcessData(array $csvData): array
    {
        $arrayProcessos = [];

        foreach ($csvData as $row) {
            
            $arrayProcessos[] = [
                'numeroprocessocommascara'          => trim($row[0]),
                'numero_processo'                    => $this->numeroProcesso(trim($row[0])),
                'nomeOrgao'                         => trim($row[2]),
                'siglaTribunal'                     => "TJMG",
                'dataDistribuicao'                  => $this->formatDate($row[3]),
                'tipoDocumento'                     => trim($row[4]),
                'poloAtivo'                         => $this->processPartes(trim($row[5])),
                'poloPassivo'                       => $this->processPartes(trim($row[6])),
                'valorCausa'                        => $this->formatCurrency($row[30]),
                'dataRevisao'                       => $this->formatDate($row[32]),
                'dt_revisao'                        => trim($row[32]),
            ];
        }

        return $arrayProcessos;
    }

    private function formatDate(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }
        
        $timestamp = strtotime($date);
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }

    private function numeroProcesso($numeroFormatado){
        return preg_replace('/[^0-9]/', '', $numeroFormatado);
    }

    private function formatCurrency(?string $value): ?float
    {
        if (empty($value)) {
            return null;
        }
        
        // Remove any non-numeric characters except decimal point
        $value = preg_replace('/[^0-9]/', '', $value);
        return is_numeric($value) ? (float)$value : null;
    }
}