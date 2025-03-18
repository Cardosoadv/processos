<?php

$fornecedores = model('FornecedoresModel')->orderBy('nome')->findAll();
$categorias = model('Financeiro/FinanceiroCategoriasModel')->orderBy('categoria')->findAll();
$users = model('ResposavelModel')->orderBy('username')->findAll();

// Verificar se o parâmetro pagarDespesa existe na URL
$pagarDespesa = isset($_GET['pagarDespesa']) ? $_GET['pagarDespesa'] : '0';

?>

<div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="togglePagarDespesa" <?= $pagarDespesa == '1' ? 'checked' : '' ?>>
            <label class="form-check-label" for="togglePagarDespesa">Pagar ao Salvar</label>
        </div>

<form method="post" id="form_despesa" name="form_despesa" action="<?= site_url('financeiro/despesas/salvar') ?>">
    <input type="hidden" name="id_despesa" value="<?= $despesa['id_despesa'] ?? '' ?>">
    <input type="hidden" name="pagarDespesa" id="pagarDespesaInput" value="<?= $pagarDespesa ?>">
    <div class="row mb-3">
        <div class="form-group col">
            <label for="despesa">Despesa</label>
            <input type="text" class="form-control" name="despesa" id="despesa" value="<?= $despesa['despesa'] ?? '' ?>" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="form-group col">
            <label for="vencimento_dt">Data de Vencimento</label>
            <input type="date" class="form-control" name="vencimento_dt" id="vencimento_dt" value="<?= $despesa['vencimento_dt'] ?? '' ?>" required>
        </div>
        <div class="form-group col">
            <label for="valor">Valor</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="text" class="form-control" name="valor" id="valor" value="<?= $despesa['valor'] ?? '' ?>" required>
            </div>
        </div>
    </div>
        <div class="row mb-3">
            <div class="form-group col">
                <label for="categoria">Categoria</label>
                <select class="form-control" name="categoria" id="categoria">
                    <option value="">Selecione uma categoria</option>
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['id_categoria'] ?>" <?= isset($despesa['categoria']) && $despesa['categoria'] == $categoria['id_categoria'] ? 'selected' : '' ?>>
                                <?= $categoria['categoria'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group col">
                <label for="fornecedor">Fornecedor</label>
                <select class="form-control" name="fornecedor" id="fornecedor">
                    <option value="">Selecione um fornecedor</option>
                    <?php if (!empty($fornecedores)): ?>
                        <?php foreach ($fornecedores as $fornecedor): ?>
                            <option value="<?= $fornecedor['id_fornecedor'] ?>" <?= isset($despesa['fornecedor']) && $despesa['fornecedor'] == $fornecedor['id_fornecedor'] ? 'selected' : '' ?>>
                                <?= $fornecedor['nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="form-group col">
                <label for="comentario">Comentário</label>
                <textarea class="form-control" name="comentario" id="comentario"><?= $despesa['comentario'] ?? '' ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label>Rateio entre Advogados</label>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="adicionarRateio()">
                        <i class="fas fa-plus"></i> Adicionar Rateio
                    </button>
                </div>
                <div id="container-rateio">
                    <?php if (!empty($users)): ?>
                        <?php
                        if (isset($despesa['rateio']) && !is_array($despesa['rateio'])) {
                            $despesa['rateio'] = json_decode($despesa['rateio'], true);
                        }

                        $rateio = $despesa['rateio'] ?? [];
                        $numero_de_despesas = count($rateio);
                        for ($i = 0; $i < max(2, $numero_de_despesas); $i++):
                        ?>
                            <div class="row mt-1 rateio-row">
                                <div class="col-8">
                                    <select name="rateio[<?= $i ?>][id]" class="form-control">
                                        <option value="">Selecione um Advogado</option>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?= $user['id'] ?>" <?= isset($rateio[$i]['id']) && $rateio[$i]['id'] == $user['id'] ? 'selected' : '' ?>>
                                                <?= $user['username'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" name="rateio[<?= $i ?>][valor]" value="<?= $rateio[$i]['valor'] ?? '' ?>">
                                        <span class="input-group-text">%</span>
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
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= site_url('financeiro/despesas/') ?>" class="btn btn-outline-secondary">Cancelar</a>
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




    function adicionarRateio() {
        const container = document.getElementById('container-rateio');
        const index = container.getElementsByClassName('rateio-row').length;

        const novaLinha = document.createElement('div');
        novaLinha.className = 'row mt-1 rateio-row';

        novaLinha.innerHTML = `
        <div class="col-8">
            <select name="rateio[${index}][id]" class="form-control">
                <option value="">Selecione um Advogado</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-3">
            <div class="input-group">
                <input type="number" step="0.01" class="form-control" name="rateio[${index}][valor]">
                <span class="input-group-text">%</span>
            </div>
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-danger btn-sm" onclick="removerRateio(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

        container.appendChild(novaLinha);
    }

    function removerRateio(button) {
        const row = button.closest('.rateio-row');
        row.remove();

        // Reindex remaining rows
        const container = document.getElementById('container-rateio');
        const rows = container.getElementsByClassName('rateio-row');
        for (let i = 0; i < rows.length; i++) {
            const select = rows[i].querySelector('select');
            const input = rows[i].querySelector('input');
            select.name = `rateio[${i}][id]`;
            input.name = `rateio[${i}][valor]`;
        }
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

// Formata o valor inicial ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const campoValor = document.getElementById('valor');
    
    if (campoValor) {
        let valor = campoValor.value;
        if (valor) {
            valor = formatarNumero(valor);
            valor = formatarAoPerderFoco(valor);
            campoValor.value = valor;
        }
    }
});

// Manipula a digitação permitindo números e vírgula
document.getElementById('valor').addEventListener('input', function(e) {
    let valorAtual = e.target.value;
    e.target.value = formatarNumero(valorAtual);
});

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

// Formata quando o campo perde o foco
document.getElementById('valor').addEventListener('blur', function(e) {
    let valor = e.target.value;
    e.target.value = formatarAoPerderFoco(valor);
});
</script>