<?php

namespace App\Libraries;

use App\Models\ProcessosMovimentosModel;


class ReceberMovimentos{

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

        $resultados = curl_exec($ch);
        curl_close($ch);
        $resultados = json_decode($resultados);

        if (empty($resultados->hits->hits[0])) {
            return ['erro' => "Erro! Não foi possível encontrar dados do processo. Isso pode acontecer porque ele é sigiloso ou está tramitando em outro tribunal."];
            die;
        }

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
                }
            }
        }
        $data = [
            'numero_processo'           => $numeroProcesso,
            'nome'                      => $nome,
            'descricao'                 => 'null',
            'descricao_complemento'     => $compleentoDescricao,
            'nome_complemento'          => $complementoNome,
            'dataHora'                  => $dataHoraMovimento,
        ];
        $processosMovimentosModel = new ProcessosMovimentosModel();
        $processosMovimentosModel->insert($data);
    }
}