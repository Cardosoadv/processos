<script>
function addAtivo() {
    const ativoDiv = document.getElementById('ativo');
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.className = 'form-control mt-2';
    newInput.name = 'poloAtivo[]';
    ativoDiv.appendChild(newInput);
}

function addPassivo() {
    const passivoDiv = document.getElementById('passivo');
    const newInputPassivo = document.createElement('input');
    newInputPassivo.type = 'text';
    newInputPassivo.className = 'form-control mt-2';
    newInputPassivo.name = 'poloPassivo[]';
    passivoDiv.appendChild(newInputPassivo);
}

</script>


<form method="post" id="form_processo" name="form_processo" action="<?= site_url('processos/salvar') ?>/<?= $processo['id_processo']?>" enctype="multipart/form-data">
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

    <div class="row mt-3 border rounded bg-custom" style="background-color: #f0f0f0;">
        <center><h3>Partes</h3></center>
        <div class="row">
            <div class="col py-3">
                <label>Polo Ativo</label>
                <div class="form-group">
                <?php if($poloAtivo):?>
                    <?php foreach ($poloAtivo as $ativo):?>
                        <input type="text" class="form-control mt-2" name="poloAtivo[]" value="<?= $ativo['nome'] ?? '' ?>">                        
                    <?php endforeach; ?>
                <?php else:?>
                    <input type="text" class="form-control mt-2" name="poloAtivo[]" value="">
                <?php endif; ?>
                <div id="ativo"></div>
                </div>
            </div>
            
            <div class="col py-3">
                <label>Polo Passivo</label>
                <div class="form-group">

                    <?php if($poloPassivo):?>
                        <?php foreach ($poloPassivo as $passivo):?>
                            <input type="text" class="form-control mt-2" name="poloPassivo[]" value="<?php echo $passivo['nome'] ?>">
                        <?php endforeach; ?>
                    <?php else:?>
                        <input type="text" class="form-control mt-2" name="poloPassivo[]" value="">   
                    <?php endif; ?>
                    <div id="passivo"></div>
                    
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <a class="btn btn-success" onclick="addAtivo()">Adicionar</a>
            </div>
            <div class="col">
                <a class="btn btn-success" onclick="addPassivo()">Adicionar</a>
            </div>
        </div>
    </div>

    <div class="row mt-3">    

        <div class="form-group col">
            <label>Data Distribuição</label>
            <input type="date" name="dataDistribuicao" class="form-control" value="<?= $processo['dataDistribuicao'] ?? '' ?>">
        </div>

        <div class="form-group col">
            <label>Valor da Causa</label>
            <input type="number" step="0.01" name="valorCausa" class="form-control" value="<?= $processo['valorCausa'] ?? '' ?>">
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
            <?= $processo['comentario'] ?? ' ' ?>
            </textarea>
        </div>
    </div>
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('/processos/') ?>" class="btn btn-outline-secondary right">Cancelar</a>
    </div>
</form>