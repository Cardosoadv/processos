<?php

namespace App\Libraries;

class ObjetoParaArray {

    public function paraArray($data) {

        $intimacoes = [];
        $items = [];

        foreach ($data->items as $itens) {

            $destinatarios = [];
            $advogados = []; // Inicializa $advogados como array DENTRO do loop externo

            foreach ($itens->destinatarios as $elemento) { // Renomeado $elementos para $elemento para melhor clareza
                $novoDestinatarios = [
                    'nome' => $elemento->nome,
                    'polo' => $elemento->polo,
                    'comunicacao_id' => $elemento->comunicacao_id,
                ];
                array_push($destinatarios, $novoDestinatarios);
            }

            foreach ($itens->destinatarioadvogados as $advogado) { // Renomeado $advogados para $advogado para evitar conflito de nomes
                $novoAdvogados = [
                    'id' => $advogado->id,
                    'comunicacao_id' => $advogado->comunicacao_id,
                    'advogado_id' => $advogado->advogado_id,
                    'created_at' => $advogado->created_at,
                    'updated_at' => $advogado->updated_at,
                    'advogado' => [
                        'id' => $advogado->advogado->id,
                        'nome' => $advogado->advogado->nome,
                        'numero_oab' => $advogado->advogado->numero_oab,
                        'uf_oab' => $advogado->advogado->uf_oab,
                    ],
                ];
                array_push($advogados, $novoAdvogados);
            }

            $novoItems = [
                'id' => $itens->id,
                'data_disponibilizacao' => $itens->data_disponibilizacao,
                'siglaTribunal' => $itens->siglaTribunal,
                'tipoComunicacao' => $itens->tipoComunicacao,
                'nomeOrgao' => $itens->nomeOrgao,
                'texto' => $itens->texto,
                'numero_processo' => $itens->numero_processo,
                'meio' => $itens->meio,
                'link' => $itens->link,
                'tipoDocumento' => $itens->tipoDocumento,
                'nomeClasse' => $itens->nomeClasse,
                'codigoClasse' => $itens->codigoClasse,
                'numeroComunicacao' => $itens->numeroComunicacao,
                'ativo' => $itens->ativo,
                'hash' => $itens->hash,
                'datadisponibilizacao' => $itens->datadisponibilizacao,
                'meiocompleto' => $itens->meiocompleto,
                'numeroprocessocommascara' => $itens->numeroprocessocommascara,
                "destinatarios" => $destinatarios,
                "destinatarioadvogados" => $advogados,
            ];
            array_push($items, $novoItems);
        }

        $intimacoes = [
            "status" => $data->status,
            "message" => $data->message,
            "count" => $data->count,
            "items" => $items,
        ];

        return $intimacoes;
    }
}