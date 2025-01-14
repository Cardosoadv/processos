<?php

namespace App\Libraries;


class LerJson{

    public function lerJson($arquivo)
    {
        $conteudoJson = file_get_contents($arquivo);
        $dados = json_decode($conteudoJson);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $dados;
        } else {
            log_message('error', 'Erro ao decodificar JSON: ' . json_last_error_msg());
            return null; // Ou lançar uma exceção, dependendo da sua necessidade
        }
    }
}

