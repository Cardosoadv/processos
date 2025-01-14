<?php

namespace App\Libraries;

use App\Libraries\ObjetoParaArray;
use App\Controllers\Intimacoes;
use Exception;

class ReceberIntimacoesJs{

        
    public function getIntimacoes($data){

        $objetoParaArray = new ObjetoParaArray();
        $intimacoes = new Intimacoes();


        //var_dump($data);
        
        //Verifica se os dados vieram como array
        if (!is_array($data)) {
            
            $arrayData = $objetoParaArray->paraArray($data);
            //Checa se a requisição foi bem sucedida
            if ($arrayData['status']==="success"){
                // Generate a unique filename
                $filename = $this->generateFilename();
                            
                // Save JSON to file
                $this->saveJsonToFile($filename, $arrayData);
                $intimacoes->parseIntimacao($arrayData, $filename);
                return ['filename' => $filename, 'data' => $arrayData];
            }
        }else{
        
            //Checa se a requisição foi bem sucedida
            if ($data['status']==="success"){
                // Generate a unique filename
                $filename = $this->generateFilename();
                
                // Save JSON to file
                $this->saveJsonToFile($filename, $data);
                $intimacoes->parseIntimacao($data, $filename);
                return ['filename' => $filename, 'data' => $data];
            }
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
            mkdir($storagePath, 0777, true);
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
        $result = file_put_contents($filename, json_encode($jsonContent));
        
        if ($result === false) {
            throw new Exception("Unable to save JSON file: {$filename}");
        }
    }
}