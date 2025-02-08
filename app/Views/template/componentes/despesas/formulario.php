<?php
$fornecedores = model('Financeiro/FinanceiroFornecedoresModel')->findAll();
$categorias = model('Financeiro/FinanceiroCategoriasModel')->findAll();
$users = model('ResposavelModel')->findAll();
?>
<form method="post" id="form_despesa" name="form_despesa" action="<?= site_url('despesas/salvar') ?>">
    <input type="hidden" name="id_despesa" value="<?= $despesas['id_despesa'] ?? '' ?>">

    <div class="row mb-3">
        <div class="form-group col">
            <label for="despesa">Despesa</label>
            <input type="text" class="form-control" name="despesa" id="despesa" value="<?= $despesas['despesa'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="vencimento_dt">Data de Vencimento</label>
            <input type="date" class="form-control" name="vencimento_dt" id="vencimento_dt" value="<?= $despesas['vencimento_dt'] ?? '' ?>" required>
        </div>
        <div class="form-group col">
            <label for="valor">Valor</label>
            <input type="number" step="0.01" class="form-control" name="valor" id="valor" value="<?= $despesas['valor'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="categoria">Categoria</label>
            <select class="form-control" name="categoria" id="categoria" required>
                <option value="">Selecione uma categoria</option>
                <!-- Opções de categorias devem ser preenchidas dinamicamente -->
                <?php if (isset($categorias) && is_array($categorias)): ?>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= isset($despesas['categoria']) && $despesas['categoria'] == $categoria['id'] ? 'selected' : '' ?>>
                            <?= $categoria['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group col">
            <label for="fornecedor">Fornecedor</label>
            <select class="form-control" name="fornecedor" id="fornecedor" required>
                <!-- Opções de fornecedores devem ser preenchidas dinamicamente -->
                <option value="">Selecione um fornecedor</option>
                <?php if (isset($fornecedores) && is_array($fornecedores)): ?>
                    <?php foreach ($fornecedores as $fornecedor): ?>
                        <option value="<?= $fornecedor['id_fornecedor'] ?>" <?= isset($despesas['fornecedor']) && $despesas['fornecedor'] == $fornecedor['id_fornecedor'] ? 'selected' : '' ?>>
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
            <textarea class="form-control" name="comentario" id="comentario"><?= $despesas['comentario'] ?? '' ?></textarea>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col-8">
            <label for="rateio">Advogado</label>
            <?php if (isset($users) && is_array($users)) : ?>
                <select name="rateio[0][id]" class="form-control mt-1" style="width: 100%;">
                    <option value="">Selecione um Advogado</option>
                    <?php foreach ($users as $user) : ?>
                        <option value="<?= $user['id'] ?>" <?= $user['id'] == ($despesas['rateio'][0]['id'] ?? '') ? 'selected' : ''?>><?= $user['username'] ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="rateio[1][id]" class="form-control  mt-1" style="width: 100%;">
                    <option value="">Selecione um Advogado</option>
                    <?php foreach ($users as $user) : ?>
                        <option value="<?= $user['id'] ?>" <?= $user['id'] == ($despesas['rateio'][1]['id'] ?? '') ? 'selected' : ''?>><?= $user['username'] ?></option>
                    <?php endforeach; ?>
                </select>
                <?php $numero_de_despesas = count(($despesas['rateio']?? [])); ?>
                <?php if ($numero_de_despesas > 2): ?>
                    <?php for ($i = 2; $i <= $numero_de_despesas; $i++):?>
                        <select name="rateio[<?= $i ?>][id]" class="form-control  mt-1" style="width: 100%;">
                            <option value="">Selecione um Advogado</option>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?= $user['id'] ?>" <?= $user['id'] == ($despesas['rateio'][ $i ]['id'] ?? '') ? 'selected' : ''?>><?= $user['username'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endfor; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="form-group col-4">
            <label for="rateio">Rateio</label>
                <input type="number" step="0.01" class="form-control" name="rateio[0][valor]  mt-1" value="<?= $despesas['rateio'][0]['valor'] ?? '' ?>">
                <input type="number" step="0.01" class="form-control" name="rateio[1][valor]  mt-1" value="<?= $despesas['rateio'][1]['valor'] ?? '' ?>">
                    <?php for ($i = 2; $i <= $numero_de_despesas; $i++):?>
                        <input type="number" step="0.01" class="form-control  mt-1" name="rateio[<?= $i?>][valor]" value="<?= $despesas['rateio'][ $i]['valor'] ?? '' ?>">
                    <?php endfor; ?>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('/despesas/') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>