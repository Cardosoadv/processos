<?php

namespace App\Traits;

trait FormataDataTrait
{

    /**
     * Formata a data para o formato brasileiro (d/m/Y).
     *
     * @param string|null $data A data a ser formatada.
     * @return string|null A data formatada ou null se a entrada for null.
     */
    public function dataParaBancoDados($data)
    {
        if (!$data) {
            return null;
        }

        $dateTime = \DateTime::createFromFormat('d/m/Y', $data);
        if (!$dateTime) {
            throw new \InvalidArgumentException('Invalid date format');
        }

        return $dateTime->format('Y-m-d');
    }

    /**
     * Formata a data para o formato brasileiro (d/m/Y).
     *
     * @param string|null $data A data a ser formatada.
     * @return string|null A data formatada ou null se a entrada for null.
     */
    public function dataDoBancoDados($data)
    {
        if (!$data) {
            return null;
        }
        $dateTime = \DateTime::createFromFormat('Y-m-d', $data);
        if (!$dateTime) {
            throw new \InvalidArgumentException('Invalid date format');
        }
        return $dateTime->format('d/m/Y');
    }
}
