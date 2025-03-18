<form method="post" id="form_conta" name="form_conta" action="<?= site_url('financeiro/contas/salvar') ?>">
    <input type="hidden" name="id_conta" value="<?= $conta['id_conta'] ?? '' ?>">

    <div class="row mb-3">
        <div class="form-group col">
            <label for="conta">Conta</label>
            <input type="text" class="form-control" name="conta" id="conta" value="<?= $conta['conta'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">

    <div class="form-group col">
            <label for="banco">Banco</label>
            <input type="text" class="form-control" name="banco" id="banco" value="<?= $conta['banco'] ?? '' ?>" required>
        </div>
        <div class="form-group col">
            <label for="agencia">Agência</label>
            <input type="text" class="form-control" name="agencia" id="agencia" value="<?= $conta['agencia'] ?? '' ?>">
        </div>

    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="numero_conta">Número da Conta</label>
            <input type="text" class="form-control" name="numero_conta" id="numero_conta" value="<?= $conta['numero_conta'] ?? '' ?>">
        </div>

        <div class="form-group col">
            <label for="pix">PIX</label>
            <input type="text" class="form-control" name="pix" id="pix" value="<?= $conta['pix'] ?? '' ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="comentarios">Comentários</label>
            <textarea class="form-control" name="comentarios" id="comentarios"><?= $conta['comentarios'] ?? '' ?></textarea>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('financeiro/contas/') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>