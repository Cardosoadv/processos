<?php

namespace App\Libraries;

use App\Controllers\Intimacoes;
use Exception;

class ReceberIntimacoes{

        private $intimacoes;

    /**
     * Função para buscar as intimações no DJEN
     * @param array $params
     */
    public function getIntimacoes($json){

    // Processa a resposta da API
    $data = json_decode($json, true);
            
    if(empty($data)||empty($data['status'])){
        return;
    } 

    //Checa se a requisição foi bem sucedida
    if ($data['status']==="success"){
                
        // Generate a unique filename
        $filename = $this->generateFilename();
                    
        // Save JSON to file
        $this->saveJsonToFile($filename, $json);
        $this->intimacoes = new Intimacoes();
        $this->intimacoes->parseIntimacao($data, $filename);
        }
    }

    /**
     * Generate a unique filename for the JSON file
     * 
     */
    private function generateFilename() {
        
        // Construindo o caminho completo para o arquivo ou pasta dentro do diretório de armazenamento
        $storagePath = WRITEPATH . '/jsons';

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        
        // Generate filename with timestamp
        $timestamp = date('YmdHis');
        $filename = "{$storagePath}/Intimacoes_{$timestamp}.json";
        
        return $filename;
    }

    /**
     * Save JSON response to file
     * 
     * @param string $filename Full path to save the file
     * @param string $jsonContent JSON content to save
     * @throws Exception If file cannot be saved
     */
    private function saveJsonToFile($filename, $jsonContent) {
        // Attempt to write the file
        $result = file_put_contents($filename, $jsonContent);
        
        if ($result === false) {
            throw new Exception("Unable to save JSON file: {$filename}");
        }
    }
}