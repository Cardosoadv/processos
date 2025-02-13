<?php
$contas = model('Financeiro/FinanceiroContasModel')->findAll(); // Assumindo que você tem um model para contas
?>

<form method="post" id="form_pagamento_despesa" name="form_pagamento_despesa" action="<?= site_url('financeiro/pagamentos/salvar') ?>">
    <input type="hidden" name="despesa_id" value="<?= $despesa['id_despesa'] ?>">  <div class="row mb-3">
        <div class="form-group col">
            <label for="pagamento_despesa_dt">Data de Pagamento</label>
            <input type="date" class="form-control" name="pagamento_despesa_dt" id="pagamento_despesa_dt" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="valor">Valor do Pagamento</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="text" class="form-control" name="valor" id="valor" required>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="conta_id">Conta</label>
            <select class="form-control" name="conta_id" id="conta_id" required>
                <option value="">Selecione uma conta</option>
                <?php if (!empty($contas)): ?>
                    <?php foreach ($contas as $conta): ?>
                        <option value="<?= $conta['id_conta'] ?>"><?= $conta['nome_conta'] ?></option>
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
                    $rateio = $despesa['rateio'] ?? []; // Use o rateio da despesa
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
        <a href="<?= site_url('/despesas/') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

<script>
    // ... (funções formatarNumero e formatarAoPerderFoco do código anterior) ...

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

        // Adiciona o evento de formatação para o novo campo de valor
        const novoCampoValor = novaLinha.querySelector('.valor-rateio');
        novoCampoValor.addEventListener('input', function(e) {
            let valorAtual = e.target.value;
            e.target.value = formatarNumero(valorAtual);
        });
        novoCampoValor.addEventListener('blur', function(e) {
            let valor = e.target.value;
            e.target.value = formatarAoPerderFoco(valor);
        });
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

    // ... (resto do código de formatação do valor principal) ...

    // Formatação dos campos de rateio ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        const camposRateio = document.querySelectorAll('.valor-rateio');
        camposRateio.forEach(campo => {
            let valor = campo.value;
            if (valor) {
                valor = formatarNumero(valor);
                valor = formatarAoPerderFoco(valor);
                campo.value = valor;
            }
            campo.addEventListener('input', function(e) {
                let valorAtual = e.target.value;
                e.target.value = formatarNumero(valorAtual);
            });
            campo.addEventListener('blur', function(e) {
                let valor = e.target.value;
                e.target.value = formatarAoPerderFoco(valor);
            });
        });
    });

</script>