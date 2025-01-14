<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\LerJson as lib;

class Lerjson extends BaseController
{
    public function index()
    {
        $lib = new lib();
        $dir = WRITEPATH . "jsons/";

        // Verifica se o diretório existe
        if (!is_dir($dir)) {
            echo "Diretório 'jsons' não encontrado em WRITEPATH.";
            return; // Importante para evitar erros
        }

        $arquivos = scandir($dir);

        foreach ($arquivos as $arquivo) {
            // Ignora "." e ".."
            if ($arquivo != "." && $arquivo != "..") {
                $caminhoCompleto = $dir . $arquivo; // Concatena o caminho completo
                if (is_file($caminhoCompleto)) { // Verifica se é um arquivo
                    echo "<h2>Arquivo: " . $arquivo . "</h2>"; // Título para cada arquivo
                    echo '<pre>';
                    $dadosJson = $lib->lerJson($caminhoCompleto); // Passa o caminho completo
                    if ($dadosJson !== null) {
                        print_r($dadosJson);
                    } else {
                        echo "Erro ao ler ou decodificar o JSON do arquivo: " . $arquivo;
                    }
                    echo '</pre>';
                }
            }
        }

        // Remova este print_r, pois já imprimimos os arquivos individualmente
        // echo '<pre>';
        // print_r($arquivos);
    }
}