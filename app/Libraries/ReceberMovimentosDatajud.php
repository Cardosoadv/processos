<?php

namespace App\Libraries;

use App\Models\ProcessosMovimentosModel;
use Exception;

class ReceberMovimentosDatajud{

    public function receberMovimentos($numeroProcesso){

        $url = "https://api-publica.datajud.cnj.jus.br/api_publica_tjmg/_search";
        
        $ch = curl_init();

        $APIkEY = getenv('API_KEY'); 

        curl_setopt_array($ch, [

            CURLOPT_URL => $url,

            CURLOPT_POST => true,

            CURLOPT_HTTPHEADER => [
                'Authorization: '.$APIkEY,
                'Content-Type: application/json',
                'x-li-format: json'
            ],

            CURLOPT_POSTFIELDS => json_encode([
                'query' => [
                    'match' => [
                        'numeroProcesso' => $numeroProcesso,
                    ]
                ],
            ]),
            
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS
        ]);

        $response = curl_exec($ch);
        curl_close($ch);
        $resultados = json_decode($response);

        if (empty($resultados->hits->hits[0])) {
            return ['erro' => "Erro! Não foi possível encontrar dados do processo. Isso pode acontecer porque ele é sigiloso ou está tramitando em outro tribunal."];
            die;
        }

        // Generate a unique filename
        $filename = $this->generateFilename();
                    
        // Save JSON to file
        $this->saveJsonToFile($filename, $response);

        // Acessando os dados
        $numeroProcesso = $resultados->hits->hits[0]->_source->numeroProcesso;
        
        // Acessando os movimentos
        $movimentos = $resultados->hits->hits[0]->_source->movimentos;
        
        foreach ($movimentos as $movimento) {

            $nome = $movimento->nome;
            $dataHoraMovimento = $movimento->dataHora;  
            
            if(! empty($movimento->complementosTabelados)){

                foreach($movimento->complementosTabelados as $complementos){
                    
                    $complementoNome = $complementos->nome;
                    $compleentoDescricao = $complementos->descricao;
                    $complementoCodigo = $complementos->codigo;
                    $complementoValor = $complementos->valor;





                    $data = [
                        'numero_processo'           => $numeroProcesso,
                        'nome'                      => $nome,
                        'descricao_complemento'     => $compleentoDescricao,
                        'nome_complemento'          => $complementoNome,
                        'codigo'                    => $complementoCodigo,
                        'valor'                     => $complementoValor,
                        'dataHora'                  => $dataHoraMovimento,
                    ];
                    $processosMovimentosModel = new ProcessosMovimentosModel();

                    //Checa se o movimento já consta no db
                    if(
                        $processosMovimentosModel->where('numero_processo', $numeroProcesso)
                        ->where('dataHora', $dataHoraMovimento)->get()->getRowArray() === null
                        ){
                            $processosMovimentosModel->insert($data);
                        }
                }
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
            mkdir($storagePath, 0755, true);
        }
        
        // Generate filename with timestamp
        $timestamp = date('YmdHis');
        $filename = "{$storagePath}/Movimentos_{$timestamp}.json";
        
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
