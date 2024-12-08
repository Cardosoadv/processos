<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Testes extends BaseController
{
    public function index()
    {

            $url = "https://api-publica.datajud.cnj.jus.br/api_publica_tjmg/_search";
    
            $ch = curl_init();

            $APIkEY = getenv('API_KEY');
            echo $APIkEY;
    
            curl_setopt_array($ch, [
    
                CURLOPT_URL => $url,
    
                CURLOPT_POST => true,
    
                CURLOPT_HTTPHEADER => [
                    'Authorization:'.$APIkEY,
                    'Content-Type: application/json',
                    'x-li-format: json'
                ],
    
                CURLOPT_POSTFIELDS => json_encode([
                    'query' => [
                        'match' => [
                            'numeroProcesso' => "50391641120198130024",
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
            echo "Número do processo: " . $resultados->hits->hits[0]->_source->numeroProcesso.PHP_EOL;
            echo "Classe do processo: " . $resultados->hits->hits[0]->_source->classe->nome.PHP_EOL;
            echo "Número do processo: " . $resultados->hits->hits[0]->_source->id.PHP_EOL;

            
            // Acessando os movimentos
            $movimentos = $resultados->hits->hits[0]->_source->movimentos;
            foreach ($movimentos as $movimento) {

               
                echo '<pre>';
                echo $movimento->nome.PHP_EOL;
                echo $movimento->dataHora.PHP_EOL;  
                
                if(! empty($movimento->complementosTabelados)){
                foreach($movimento->complementosTabelados as $complementos){
                    
                    echo "passou aqui";
                    echo '<pre>';
                    echo $complementos->nome;
                    echo $complementos->descricao;
                    //print_r($complementos);
                }
            }

            }
           

            echo '</pre>'. "inicio do Json            ";
            var_dump($resultados);
    }
}
