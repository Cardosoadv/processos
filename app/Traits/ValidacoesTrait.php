<?php

namespace App\Traits;

trait ValidacoesTrait
{


    #------------------------------------------------------------------------------------------------
    #                            VALIDAÇÃO CPF OU CNPJ
    #------------------------------------------------------------------------------------------------

     /**
     * Função para validar CPF ou CNPJ
     */
    public function validarCpfCnpj($cpf_cnpj)
    {
        // Remove caracteres não numéricos
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $cpf_cnpj);

        // Verifica se é CPF
        if (strlen($cpf_cnpj) == 11) {
            return $this->validarCpf($cpf_cnpj);
        }

        // Verifica se é CNPJ
        if (strlen($cpf_cnpj) == 14) {
            return $this->validarCnpj($cpf_cnpj);
        }

        return false;
    }

    /**
     * Função para validar CPF
     */
    private function validarCpf($cpf)
    {
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação do CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * Função para validar CNPJ
     */
    private function validarCnpj($cnpj)
    {
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Validação do CNPJ
        for ($t = 12; $t < 14; $t++) {
            for ($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++) {
                $d += $cnpj[$c] * $p;
                $p = ($p == 2 || $p == 9) ? 9 : --$p;
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}