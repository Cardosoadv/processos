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

    <button type="submit" class="btn btn-primary">Salvar</button>
</form>