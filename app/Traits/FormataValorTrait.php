<?php

namespace App\Traits;

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

    public function formataValorCpf_cnpj($valor)
    {
        $valor = preg_replace('/[^0-9]/', '', $valor);
        if (strlen($valor) == 11) {
            return $this->formataCpf($valor);
        }
        if (strlen($valor) == 14) {
            return $this->formataCnpj($valor);
        }
        return "Erro ao formatar o valor";
    }

    private function formataCpf($cpf)
    {

        $cpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
        return $cpf;
    }

    private function formataCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        $cnpj = substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
        return $cnpj;
    }


}

