<form action="<?= base_url('objetos/salvar') ?>" method="post">
    <input type="hidden" name="id_objeto" value="<?= esc($objeto['id_objeto'] ?? '') ?>">
    <div class="row mb-2">
        <div class="form-group col-6">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" class="form-control col" id="cidade" name="cidade" value="<?= esc($objeto['cidade'] ?? '') ?>">
        </div>
        <div class="form-group col-6">
            <label for="bairro" class="form-label">Bairro</label>
            <input type="text" class="form-control col" id="bairro" name="bairro" value="<?= esc($objeto['bairro'] ?? '') ?>">
        </div>
    </div>
    <div class="row mb-2">
        <div class="form-group col-6">
            <label for="quadra" class="form-label">Quadra</label>
            <input type="text" class="form-control col" id="quadra" name="quadra" value="<?= esc($objeto['quadra'] ?? '') ?>">
        </div>
        <div class="form-group col-6">
            <label for="lote" class="form-label">Lote</label>
            <input type="text" class="form-control col" id="lote" name="lote" value="<?= esc($objeto['lote'] ?? '') ?>">
        </div>
    </div>
    <div class="row mb-2">
    <div class="form-group col-6">
            <label for="cod_interno" class="form-label">Código</label>
            <input type="text" class="form-control col" id="cod_interno" name="cod_interno" value="<?= esc($objeto['cod_interno'] ?? '') ?>">
        </div>
    </div>

    <div class="row mb-2">
        <div class="form-group col-9">
            <label for="logradouro" class="form-label">Logradouro</label>
            <input type="text" class="form-control col" id="logradouro" name="logradouro" value="<?= esc($objeto['logradouro'] ?? '') ?>">
        </div>
        <div class="form-group col-3">
            <label for="numero" class="form-label">Número</label>
            <input type="text" class="form-control col" id="numero" name="numero" value="<?= esc($objeto['numero'] ?? '') ?>">
        </div>
        <div class="form-group col-9">
            <label for="complemento" class="form-label">Complemento</label>
            <input type="text" class="form-control col" id="complemento" name="complemento" value="<?= esc($objeto['complemento'] ?? '') ?>">
        </div>
    </div>

    <div class="row mb-2">
        <div class="form-group col-9">
            <label for="comentarios" class="form-label">Comentários</label>
            <textarea class="form-control col" id="comentarios" name="comentarios" rows="3">
                <?= esc($objeto['logradouro'] ?? '') ?>
            </textarea>    
        </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
</form>