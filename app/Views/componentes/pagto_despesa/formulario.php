<?php

$contas = model('Financeiro/FinanceiroContasModel')->orderBy('conta')->findAll();
$users  = model('ResposavelModel')->orderBy('username')->findAll();
$despesas = model('Financeiro/FinanceiroDespesasModel')->orderBy('despesa')->findAll();
?>
<div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="togglePagarDespesa" <?= $pagarDespesa == '1' ? 'checked' : '' ?>>
            <label class="form-check-label" for="togglePagarDespesa">Pagar ao Salvar</label>
        </div>


<form method="post" id="form_pagamento_despesa" name="form_pagamento_despesa" action="<?= site_url('financeiro/pagamentoDespesas/salvar') ?>">
    <input type="hidden" name="id_pgto_despesa" value="<?= $pagtoDespesa['id_pgto_despesa']??'' ?>">  
    <input type="hidden" name="pagarDespesa" id="pagarDespesaInput" value="<?= $pagarDespesa ?>">
    
    <div class="row mb-3">
        <div class="form-group col">
            <label for="despesa_id">Despesa</label>
            <select class="form-control" name="despesa_id" id="despesa_id" required>
                <option value="">Selecione uma despesa</option>
                <?php if (!empty($despesas)): ?>
                    <?php foreach ($despesas as $despesa): ?>
                        <option value="<?= $despesa['id_despesa'] ?>" <?=$despesa['id_despesa'] == ($pagtoDespesa['despesa_id']??'') ? 'selected' : ''?>><?= $despesa['despesa'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group col">
            <label for="pagamento_despesa_dt">Data de Pagamento</label>
            <input type="date" class="form-control" name="pagamento_despesa_dt" id="pagamento_despesa_dt" value="<?= $pagtoDespesa['pagamento_despesa_dt'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="valor">Valor do Pagamento</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="text" class="form-control" name="valor" id="valor" value="<?= $pagtoDespesa['valor'] ?? '' ?>" required>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="conta_id">Conta</label>
            <select class="form-control" name="conta_id" id="conta_id" required>
                <option value="0">Selecione uma conta</option>
                <?php if (!empty($contas)): ?>
                    <?php foreach ($contas as $conta): ?>
                        <option value="<?= $conta['id_conta'] ?? '' ?>" <?= $conta['id_conta'] == ($pagtoDespesa['conta_id']??'') ? 'selected' : ''?>><?= $conta['conta'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="comentario">Comentário</label>
            <textarea class="form-control" name="comentario" id="comentario"></textarea>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group">
            <label>Rateio entre Advogados (Valores em Reais)</label>
            <div id="container-rateio-pagamento">
                <?php if (!empty($users)): ?>
                    <?php
                    $rateio = $pagtoDespesa['rateio'] ?? []; // Use o rateio da despesa
                    $numero_de_despesas = count($rateio);
                    for ($i = 0; $i < max(2, $numero_de_despesas); $i++): // Exibe pelo menos 2 linhas
                        ?>
                        <div class="row mt-1 rateio-row">
                            <div class="col-6">
                                <select name="rateio[<?= $i ?>][id]" class="form-control">
                                    <option value="">Selecione um Advogado</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id'] ?>" <?= isset($rateio[$i]['id']) && $rateio[$i]['id'] == $user['id'] ? 'selected' : '' ?>>
                                            <?= $user['username'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-5">
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control valor-rateio" name="rateio[<?= $i ?>][valor]" value="<?= $rateio[$i]['valor'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removerRateio(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="adicionarRateioPagamento()">
                <i class="fas fa-plus"></i> Adicionar Rateio
            </button>
        </div>
    </div>


    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar Pagamento</button>
        <a href="<?= site_url('financeiro/pagamentoDespesas/') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

<script>

    // Toggle para o parâmetro pagarDespesa
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('togglePagarDespesa');
        const inputPagarDespesa = document.getElementById('pagarDespesaInput');
        
        toggleBtn.addEventListener('change', function() {
            // Atualizar o valor do input hidden
            inputPagarDespesa.value = this.checked ? '1' : '0';
            
            // Atualizar a URL com o novo parâmetro sem recarregar a página
            const url = new URL(window.location.href);
            url.searchParams.set('pagarDespesa', this.checked ? '1' : '0');
            window.history.pushState({}, '', url.toString());
        });
    });

    // Função para inicializar os campos de rateio
    function inicializarCamposRateio() {
        // Seleciona todos os campos de valor-rateio existentes
        const camposRateio = document.querySelectorAll('.valor-rateio');
        
        // Adiciona os eventos a cada campo
        camposRateio.forEach(campo => {
            // Formatação inicial (se já tiver valor)
            let valor = campo.value;
            if (valor) {
                valor = formatarNumero(valor);
                valor = formatarAoPerderFoco(valor);
                campo.value = valor;
            }
            
            // Remove eventos existentes para evitar duplicação
            campo.removeEventListener('input', handleInput);
            campo.removeEventListener('blur', handleBlur);
            
            // Adiciona novos event listeners
            campo.addEventListener('input', handleInput);
            campo.addEventListener('blur', handleBlur);
        });
    }

    // Funções de manipulação de eventos para reutilização
    function handleInput(e) {
        let valorAtual = e.target.value;
        e.target.value = formatarNumero(valorAtual);
    }

    function handleBlur(e) {
        let valor = e.target.value;
        e.target.value = formatarAoPerderFoco(valor);
    }

    function formatarNumero(valor) {
        if (!valor) return '';
        
        // Substitui ponto por vírgula
        valor = valor.replace(/\./g, ',');
        
        // Remove tudo que não é número ou vírgula
        valor = valor.replace(/[^\d,]/g, '');
        
        // Garante que só existe uma vírgula
        const partes = valor.split(',');
        if (partes.length > 2) {
            valor = partes[0] + ',' + partes[1];
        }
        
        // Se existir parte decimal, limita a 2 dígitos
        if (partes.length === 2) {
            valor = partes[0] + ',' + partes[1].substring(0, 2);
        }
        
        return valor;
    }

    function formatarAoPerderFoco(valor) {
        if (!valor) {
            return '0,00';
        }
        
        // Se não tem vírgula, adiciona ,00
        if (!valor.includes(',')) {
            valor = valor + ',00';
        } else {
            // Se tem vírgula mas não tem 2 casas decimais
            const partes = valor.split(',');
            if (partes[1].length === 0) {
                valor = valor + '00';
            } else if (partes[1].length === 1) {
                valor = valor + '0';
            }
        }
        
        // Formata com separadores de milhar
        const partes = valor.split(',');
        const parteInteira = partes[0].replace(/\D/g, '');
        const numeroFormatado = Number(parteInteira).toLocaleString('pt-BR').replace(/,/g, '.') + ',' + partes[1];
        
        return numeroFormatado;
    }

    function adicionarRateioPagamento() {
        const container = document.getElementById('container-rateio-pagamento');
        const index = container.getElementsByClassName('rateio-row').length;

        const novaLinha = document.createElement('div');
        novaLinha.className = 'row mt-1 rateio-row';

        novaLinha.innerHTML = `
            <div class="col-6">
                <select name="rateio[${index}][id]" class="form-control">
                    <option value="">Selecione um Advogado</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-5">
                <div class="input-group">
                    <span class="input-group-text">R$</span>
                    <input type="text" class="form-control valor-rateio" name="rateio[${index}][valor]">
                </div>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="removerRateio(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        container.appendChild(novaLinha);
        
        // Agora inicializa os campos, incluindo o novo campo adicionado
        inicializarCamposRateio();
    }

    function removerRateio(button) {
        const row = button.closest('.rateio-row');
        row.remove();

        // Reindex remaining rows
        const container = document.getElementById('container-rateio-pagamento');
        const rows = container.getElementsByClassName('rateio-row');
        for (let i = 0; i < rows.length; i++) {
            const select = rows[i].querySelector('select');
            const input = rows[i].querySelector('input');
            select.name = `rateio[${i}][id]`;
            input.name = `rateio[${i}][valor]`;
        }
    }

    // Inicialização quando a página carrega
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa o campo de valor principal
        const campoValor = document.getElementById('valor');
        if (campoValor) {
            let valor = campoValor.value;
            if (valor) {
                valor = formatarNumero(valor);
                valor = formatarAoPerderFoco(valor);
                campoValor.value = valor;
            }
            
            // Adiciona eventos ao campo de valor principal
            campoValor.addEventListener('input', handleInput);
            campoValor.addEventListener('blur', handleBlur);
        }
        
        // Inicializa os campos de rateio
        inicializarCamposRateio();
    });
</script>