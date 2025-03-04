<form method="post" id="form_categoria" name="form_categoria" action="<?= site_url('financeiro/categorias/salvar') ?>">
    <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?? '' ?>">

    <div class="row mb-3 align-items-center">
        <div class="form-group col-8">
            <label for="categoria">Categoria</label>
            <input type="text" class="form-control" name="categoria" id="categoria" value="<?= $categoria['categoria'] ?? '' ?>" required>
        </div>

        <div class="form-group col-4">
            <label for="cor">Cor</label>
            <input type="color" class="form-control" style="height: 38px; padding: 0.5rem 0.75rem; box-sizing: border-box;" name="cor" id="cor" value="<?= $categoria['cor'] ?? '' ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group">
            <label for="comentarios">Comentários</label>
            <textarea class="form-control" style="weight: 100px" name="comentarios" id="comentarios"><?= $categoria['comentarios'] ?? '' ?></textarea>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('financeiro/categorias/') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>