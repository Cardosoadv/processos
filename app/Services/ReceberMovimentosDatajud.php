<?php

namespace App\Services;

use App\Models\ProcessosMovimentosModel;
use Exception;

class ReceberMovimentosDatajud{

    public function receberMovimentos(string $tribunal, string $numeroProcesso){
        
        $url = null;

        // Mapeamento de tribunais para URLs da API
        $tribunais = [
            '813' => "https://api-publica.datajud.cnj.jus.br/api_publica_tjmg/_search", // TJMG
            '807' => "https://api-publica.datajud.cnj.jus.br/api_publica_tjdft/_search",// TJDFT
            '826' => "https://api-publica.datajud.cnj.jus.br/api_publica_tjsp/_search", // TJSP
            '503' => "https://api-publica.datajud.cnj.jus.br/api_publica_trt3/_search", // TRT3
            '401' => "https://api-publica.datajud.cnj.jus.br/api_publica_trf1/_search", // TRF1
            '406' => "https://api-publica.datajud.cnj.jus.br/api_publica_trf6/_search", // TRF6
        ];

    
        if (array_key_exists($tribunal, $tribunais)) {
            $url = $tribunais[$tribunal];
            
        } else {
            // Tribunal não encontrado
            error_log("Tribunal não encontrado: tribunal={$tribunal}");
            return null; // Retorna null em caso de erro
        }

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
            $data = [
                'erro' => "Erro! Não foi possível encontrar dados do processo. Isso pode acontecer porque ele é sigiloso ou está tramitando em outro tribunal.",
                'numero_movimentos' => 0,
                'movimentos_salvos' => 0,
                'movimentos_ignorados' => 0,
            ];            
            return $data;
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
        $numeroMovimentos = count($movimentos);
        $movimentosSalvos = 0;
        $movimentosIgnorados = 0;
        
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
                            $movimentosSalvos++;
                        }else{
                            $movimentosIgnorados++;
                        }
                }
            }
        }

        return $data = [
            'json_filename'         => $filename,
            'numero_movimentos'     => $numeroMovimentos,
            'movimentos_salvos'     => $movimentosSalvos,
            'movimentos_ignorados'  => $movimentosIgnorados,
            'erro'                  => null,
        ];
        
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
