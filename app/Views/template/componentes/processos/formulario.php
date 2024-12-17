<form method="post" id="form_processo" name="form_processo" action="<?= site_url('/processos/atualizar/') . $processo['id_processo'] ?>" enctype="multipart/form-data">
    <input type="hidden" name="id_processo" class="form-control" value="<?= $processo['id_processo'] ?? '' ?>">
        
    <div class="row mb-3">
    
        <div class="form-group col">
        <label>Numero do Processo</label>
        <input type="text" id="numeroprocessocommascara" name="numeroprocessocommascara" class="form-control" value="<?= $processo['numeroprocessocommascara'] ?? '' ?>" onchange="mask(this)">
        </div>

        <div class="form-group col">
            <label>Classe</label>
            <input type="text" name="tipoDocumento" class="form-control" value="<?= $processo['tipoDocumento'] ?? '' ?>">
        </div>

    </div>



    <div class="row mt-3">

        <div class="form-group">
            <label>Juízo</label>
            <input type="text" name="nomeOrgao" class="form-control" value="<?= $processo['nomeOrgao'] ?? '' ?>">
        </div>

    </div>

    <div class="row mt-3 border rounded">
        <label>Partes</label>

        <div class="form-group">
            <label>Ativo</label>
            <input type="text" name="">


    </div>


    <div class="row mt-3">    

        <div class="form-group col">
            <label>Data Distribuição</label>
            <input type="date" name="dt_distribuicao" class="form-control" value="<?= $processo['dataDistribuicao'] ?? '' ?>">
        </div>

        <div class="form-group col">
            <label>Valor da Causa</label>
            <input type="number" step="0.01" name="vlr_causa" class="form-control" value="<?= $processo['valorCausa'] ?? '' ?>">
        </div>

        <div class="form-group col">
            <label>Valor da Condenação</label>
            <input type="number" step="0.01" name="valorCondenacao" class="form-control" value="<?= $processo['valorCondenacao'] ?? '' ?>">
        </div>
    </div>
    <div class="row mt-3">
        <div class="form-group">
            <label>Comentário</label>
            <textarea class="form-control" name="comentario" id="editor" aria-label="Comentário">   
            <p>Tenho que testar novamente!</p>
            <?= $processo['comentario'] ?? ' ' ?>
            </textarea>
        </div>
    </div>
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('/processos/') ?>" class="btn btn-outline-secondary right">Cancelar</a>
    </div>
</form>