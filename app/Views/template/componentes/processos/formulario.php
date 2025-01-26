<?php
$clientes = model('ClientesModel')->findAll();
?>

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
            mostrarMensagem('Número de processo inválido', 'error');
            return;
        }
        const primeiraParte = maskPartes[1];
        const segundaParte = maskPartes[2];
        const terceiraParte = maskPartes[3];
        const quartaParte = maskPartes[4];
        const quintaParte = maskPartes[5];
        const sextaParte = maskPartes[6];
        var mask = primeiraParte + "-" + segundaParte + "." + terceiraParte + "." + quartaParte + "." + quintaParte + "." + sextaParte;
        input.value = mask;
        verificaExistenciaProcesso(mask);
    }

    async function verificaExistenciaProcesso(numeroProcesso){
        // Faz a chamada AJAX para remover a etiqueta do banco de dados
        try {
        const response = await fetch('<?= site_url('processos/verificaprocessoexiste') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                numeroprocessocommascara: numeroProcesso,
            }),
        });

        const data = await response.json();

        if (data.existe === true) {
            const urlAtual = window.location.href;
            const urlRedirecionamento = `<?= site_url('processos/consultarprocesso') ?>/${data.idProcesso}`;

            if (urlAtual === urlRedirecionamento) {
                return true; // Evita o redirecionamento
            }
            window.location.href = `<?= site_url('processos/consultarprocesso') ?>/${data.idProcesso}`;
            return true;
        } else {
            mostrarMensagem(data.msg, 'success');
            return false;
        }

    } catch (error) {
        console.error('Erro ao pesquisar processo:', error);
        mostrarMensagem('Erro ao pesquisar processo. Verifique o console para mais detalhes.', 'error');
        return false;
    }    
                
    }


</script>


<form method="post" id="form_processo" name="form_processo" action="<?= site_url('processos/salvar') ?>" enctype="multipart/form-data">
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
            <label>Titulo</label>
            <input type="text" name="titulo_processo" class="form-control" value="<?= $processo['titulo_processo'] ?? '' ?>">
        </div>
    </div>


    <div class="row mt-3">
        <div class="form-group">
            <label>Juízo</label>
            <input type="text" name="nomeOrgao" class="form-control" value="<?= $processo['nomeOrgao'] ?? '' ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="form-group col-10">
            <label>Cliente</label>
            <select name="cliente_id" class="form-control">
                <option value="">Selecione um Cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>" <?= ($processo['cliente_id'] == $cliente['id_cliente']) ? 'selected' : '' ?>><?= $cliente['nome'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-2">
            <a href="<?= base_url('clientes/novo/') ?>" class="btn btn-success">
                Novo Cliente
            </a>
        </div>
    </div>

    <div class="row mt-3 border rounded bg-custom" style="background-color: #f0f0f0;">
        <center>
            <h3>Partes</h3>
        </center>
        <div class="row">
            <!-- Polo Ativo -->
            <div class="col py-3">
                <label>Polo Ativo</label>
                <div class="form-group">
                    <?php if ($poloAtivo ?? null): ?>
                        <?php foreach ($poloAtivo as $ativo): ?>
                            <input type="text" class="form-control mt-2" name="poloAtivo[]" value="<?= $ativo['nome'] ?? '' ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <input type="text" class="form-control mt-2" name="poloAtivo[]" value="">
                    <?php endif; ?>
                    <div id="ativo"></div>
                </div>
            </div>
            <!-- Polo Passivo -->
            <div class="col py-3">
                <label>Polo Passivo</label>
                <div class="form-group">
                    <?php if ($poloPassivo ?? null): ?>
                        <?php foreach ($poloPassivo as $passivo): ?>
                            <input type="text" class="form-control mt-2" name="poloPassivo[]" value="<?php echo $passivo['nome'] ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
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

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="row">
                <div class="form-group col-6">
                    <label for="dataRevisao">Data da Revisão</label>
                    <input type="date" class="form-control" id="dataRevisao" name="dataRevisao" value="<?= $processo['dataRevisao'] ?? '' ?>">
                </div>

                <div id="dataEncerramento" class="form-group col-6 <?= (($processo['encerrado']?? 0) == 1) ? 'visually-hidden' : '' ?>">
                    <label for="data_encerramento">Data do Encerramento</label>
                    <input type="date" class="form-control" id="data_encerramento" name="data_encerramento" value="<?= $processo['data_encerramento'] ?? '' ?>">
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="encerrado">Situação do Processo</label>
                <div class="btn-group w-100" role="group" aria-label="Encerrado">
                    <input type="checkbox" class="btn-check" id="encerrado" name="encerrado" value="<?= $processo['encerrado'] ?? 0 ?>" autocomplete="off" <?= (($processo['encerrado']?? 0) == 1) ? 'checked' : '' ?>>
                    <label class="btn w-100" id="situacao" for="encerrado">
                        <?= (($processo['encerrado']?? 0) == 1) ? 'Encerrado' : 'Ativo' ?>
                    </label>
                </div>
            </div>
            


        </div> 
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= site_url('/processos/') ?>" class="btn btn-outline-secondary right">Cancelar</a>
        </div>
    </div>
</form>

<script>
    const checkbox = document.getElementById('encerrado');
    const label = document.getElementById('situacao');
    const dataEncerramento = document.getElementById('dataEncerramento');

    function atualizarBotao() {
        if (checkbox.checked) {
            label.textContent = 'Encerrado';
            label.classList.remove('btn-outline-success');
            label.classList.add('btn-outline-danger');
            this.value = 1;
            dataEncerramento.classList.remove('visually-hidden');
        } else {
            label.textContent = 'Ativo';
            label.classList.remove('btn-outline-danger');
            label.classList.add('btn-outline-success');
            this.value = 0;
            dataEncerramento.classList.add('visually-hidden');
        }
    }

    checkbox.addEventListener('change', atualizarBotao);

    // Chama a função inicialmente para definir o estado correto ao carregar a página
    atualizarBotao();
</script>