<?php

namespace App\Services;


use Exception;

class ReceberIntimacoes
{

    private IntimacoesService $intimacoesServices;

    public function __construct(){
        $this->intimacoesServices = new IntimacoesService();
    }

    /**
     * Função para buscar as intimações no DJEN
     * @param array $params
     */
    public function getIntimacoes(array | string $params)
    {

        $apiUrl = 'https://comunicaapi.pje.jus.br/api/v1/comunicacao';

        // Construindo a URL com os parâmetros
        $query = http_build_query($params);
        $apiUrl .= '?' . $query;

        // Iniciando a sessão cURL
        $ch = curl_init();

        // Configurando as opções da requisição
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: insomnia/10.1.1'
        ));

        // Executa a requisição e obtém a resposta
        $response = curl_exec($ch);

        // Verifica se houve algum erro
        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
            // Fecha a sessão cURL
            curl_close($ch);
            return;
        } else {
            // Processa a resposta da API
            $data = json_decode($response, true);

            if (empty($data) || empty($data['status'])) {
                return;
            }
            // Fecha a sessão cURL
            curl_close($ch);

            //Checa se a requisição foi bem sucedida
            if ($data['status'] === "success") {

                // Generate a unique filename
                $filename = $this->generateFilename();

                // Save JSON to file
                $this->saveJsonToFile($filename, $response);
                $this->intimacoesServices->processarIntimacoes($data, $filename, user_id());
            } else {
                $s = $data;
                return view('testes', $s);
            }
        }
    }

    /**
     * Generate a unique filename for the JSON file
     * 
     */
    private function generateFilename()
    {

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
    private function saveJsonToFile($filename, $jsonContent)
    {
        // Attempt to write the file
        $result = file_put_contents($filename, $jsonContent);

        if ($result === false) {
            throw new Exception("Unable to save JSON file: {$filename}");
        }
    }
}
