<form method="post" id="form_processo" name="form_processo" action="<?= site_url('/processos/atualizar/') . $processo['id_processo'] ?>" enctype="multipart/form-data">
    <input type="hidden" name="id_processo" class="form-control" value="<?= $processo['id_processo'] ?? '' ?>">
    <div class="form-group col-5">
        <label>Classe</label>
        <input type="text" name="tipoDocumento" class="form-control" value="<?= $processo['tpoDocumento'] ?? '' ?>">
    </div>
    <div class="form-group col-5">
        <label>Ação</label>
        <input type="text" name="acao" class="form-control" value="">
    </div>
    <div class="form-group">
        <label>Numero do Processo</label>
        <input type="text" id="numero_processo" name="numero_processo" class="form-control" value="<?= $processo['numero_processo'] ?? '' ?>" onchange="mask(this)">
    </div>
    <div class="row mt-3">
        <div class="form-group">
            <label>Juízo</label>
            <input type="text" name="nomeOrgao" class="form-control" value="<?= $processo['nomeOrgao'] ?? '' ?>">
        </div>
        <div class="form-group col-3">
            <label>Valor da Causa</label>
            <input type="number" step="0.01" name="vlr_causa" class="form-control" value="<?= $processo['valorCausa'] ?? '' ?>">
        </div>
        <div class="form-group col-3">
            <label>Data Distribuição</label>
            <input type="date" name="dt_distribuicao" class="form-control" value="<?= $processo['dataDistribuicao'] ?? '' ?>">
        </div>
        <div class="form-group col-3">
            <label>Valor da Condenação</label>
            <input type="number" step="0.01" name="valorCondenacao" class="form-control" value="<?= $processo['valorCondenacao'] ?? '' ?>">
        </div>
    </div>
    <div class="row mt-3">
        <div class="form-group">
            <label>Comentário</label>
            <textarea class="form-control" name="comentario" aria-label="Comentário">
                <?= $processo['comentario'] ?? '' ?>
            </textarea>
        </div>
    </div>
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('/processos/') ?>" class="btn btn-outline-secondary right">Cancelar</a>
    </div>
</form>