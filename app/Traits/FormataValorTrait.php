<?php

namespace app\Traits;

trait FormataValorTrait
{
    /**
     * Converte um valor formatado como string (ex: "1.234,56") para float (ex: 1234.56).
     *
     * @param string|null $valor Valor formatado (ex: "1.234,56").
     * @return float|null Retorna o valor no formato numérico ou null se o valor for inválido.
     * @throws \InvalidArgumentException Se o valor não puder ser convertido.
     */
    public function formatarValorParaBanco($valor)
    {
        log_message('debug', 'Valor recebido: ' . $valor);
        if ($valor === null || $valor === '') {
            return null;
        }

        if (is_numeric($valor)) {
            return (float) $valor;
        }

        if (!is_string($valor)) {
            throw new \InvalidArgumentException('O valor deve ser uma string.');
        }

        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);

        if (!is_numeric($valor)) {
            throw new \InvalidArgumentException('O valor não pôde ser convertido para float.');
        }
        log_message('debug', 'Valor convertido: ' . $valor);
        return (float) $valor;
    }
}