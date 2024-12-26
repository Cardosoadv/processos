<script>
/**
 * Função para adicionar um campo de polo ativo
 */
function addAtivo() {
    const ativoDiv = document.getElementById('ativo');
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.className = 'form-control mt-2';
    newInput.name = 'poloAtivo[]';
    ativoDiv.appendChild(newInput);
}
/**
 * Função para adicionar um campo de polo passivo
 */
function addPassivo() {
    const passivoDiv = document.getElementById('passivo');
    const newInputPassivo = document.createElement('input');
    newInputPassivo.type = 'text';
    newInputPassivo.className = 'form-control mt-2';
    newInputPassivo.name = 'poloPassivo[]';
    passivoDiv.appendChild(newInputPassivo);
}

/**
 * Função para formatar o campo de número de processo
 * @param string input
 */
function mask(input) {
    var value = input.value.replace(/\D/g, '').substring(0, 20);
    const regex = /^(\d{7})(\d{2})(\d{4})(\d{1})(\d{2})(\d{4})$/;
    const maskPartes = regex.exec(value);
    if (!maskPartes) {
      console.log("NUP inválida");
    }
    const primeiraParte = maskPartes[1];
    const segundaParte = maskPartes[2];
    const terceiraParte = maskPartes[3];
    const quartaParte = maskPartes[4];
    const quintaParte = maskPartes[5];
    const sextaParte = maskPartes[6];
    var mask = primeiraParte + "-" + segundaParte + "." + terceiraParte + "." + quartaParte + "." + quintaParte + "." + sextaParte;
    input.value = mask;
  }

</script>


<form method="post" id="form_processo" name="form_processo" action="<?= site_url('processos/salvar') ?>/<?= $processo['id_processo']?? ''?>" enctype="multipart/form-data">
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
            <!-- Polo Ativo -->
            <div class="col py-3">
                <label>Polo Ativo</label>
                <div class="form-group">
                <?php if($poloAtivo ?? null):?>
                    <?php foreach ($poloAtivo as $ativo):?>
                        <input type="text" class="form-control mt-2" name="poloAtivo[]" value="<?= $ativo['nome'] ?? '' ?>">                        
                    <?php endforeach; ?>
                <?php else:?>
                    <input type="text" class="form-control mt-2" name="poloAtivo[]" value="">
                <?php endif; ?>
                <div id="ativo"></div>
                </div>
            </div>
            <!-- Polo Passivo -->
            <div class="col py-3">
                <label>Polo Passivo</label>
                <div class="form-group">
                    <?php if($poloPassivo ?? null):?>
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
            <label>Risco</label>
            <select name="risco" class="form-control">
                <option value="Possível" <?= isset($processo['risco']) && $processo['risco'] == 'Possível' ? 'selected' : '' ?>>Possível</option>
                <option value="Provável" <?= isset($processo['risco']) && $processo['risco'] == 'Provável' ? 'selected' : '' ?>>Provável</option>
                <option value="Remoto" <?= isset($processo['risco']) && $processo['risco'] == 'Remoto' ? 'selected' : '' ?>>Remoto</option>
            </select>
        </div>

        
    </div>
    <div class="row mt-3">

        <div class="form-group col">
            <label>Resultado</label>
            <select name="resultado" class="form-control">
                <option value="Não Finalizado" <?= isset($processo['resultado']) && $processo['resultado'] == 'Não Finalizado' ? 'selected' : '' ?>>Não Finalizado</option>
                <option value="Sucesso" <?= isset($processo['resultado']) && $processo['resultado'] == 'Sucesso' ? 'selected' : '' ?>>Sucesso</option>
                <option value="Sucesso Parcial" <?= isset($processo['resultado']) && $processo['resultado'] == 'Sucesso Parcial' ? 'selected' : '' ?>>Sucesso Parcial</option>
                <option value="Derrota" <?= isset($processo['resultado']) && $processo['resultado'] == 'Derrota' ? 'selected' : '' ?>>Derrota</option>
            
            </select>
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