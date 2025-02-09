<?php

class FormatarValorTrait{



        /**
     * Função para formatar o valor para o banco de dados
     *
     * @param string $valor Valor formatado (ex: "1.234,56")
     * @return float Valor no formato numérico (ex: 1234.56)
     */
    private function formatarValorParaBanco($valor)
    {

        // If valor is null or empty, return null
        if (empty($valor)) {
            return null;
        }

        // Remove os pontos (separadores de milhar)
        $valor = str_replace('.', '', $valor);

        // Substitui a vírgula (separador decimal) por ponto
        $valor = str_replace(',', '.', $valor);

        // Converte para float
        return (float) $valor;
    }
}