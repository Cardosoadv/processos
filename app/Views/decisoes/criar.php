<form action="<?= base_url('/decisoes/salvar') ?>" method="post">
    <label for="numero_processo">Número do Processo:</label>
    <input type="text" name="numero_processo" id="numero_processo"><br>

    <label for="tribunal">Tribunal:</label>
    <input type="text" name="tribunal" id="tribunal"><br>

    <label for="data_decisao">Data da Decisão:</label>
    <input type="date" name="data_decisao" id="data_decisao"><br>

    <label for="ementa">Ementa:</label>
    <textarea name="ementa" id="ementa"></textarea><br>

    <div id="novos-atributos">
        <h3>Adicionar Novo Atributo</h3>
        <div class="atributo">
            <input type="text" name="novos_atributos[0][chave]" placeholder="Nome do Atributo (key)">
            <input type="text" name="novos_atributos[0][valor]" placeholder="Valor do Atributo">
            <button type="button" class="remover-atributo">Remover</button>
        </div>
    </div>
    <button type="button" id="adicionar-atributo">Adicionar Mais Atributos</button><br>

    <button type="submit">Salvar</button>
</form>

<script>
    const adicionarAtributoBtn = document.getElementById('adicionar-atributo');
    const novosAtributosDiv = document.getElementById('novos-atributos');
    let contadorAtributos = 1;

    adicionarAtributoBtn.addEventListener('click', () => {
        const novoAtributoDiv = document.createElement('div');
        novoAtributoDiv.classList.add('atributo');
        novoAtributoDiv.innerHTML = `
            <input type="text" name="novos_atributos[${contadorAtributos}][chave]" placeholder="Nome do Atributo (key)">
            <input type="text" name="novos_atributos[${contadorAtributos}][valor]" placeholder="Valor do Atributo">
            <button type="button" class="remover-atributo">Remover</button>
        `;
        novosAtributosDiv.appendChild(novoAtributoDiv);
        contadorAtributos++;

        // Adiciona o listener para o botão de remover nos novos elementos
        const botoesRemover = document.querySelectorAll('.remover-atributo');
        botoesRemover.forEach(botao => {
          botao.addEventListener('click', (event) => {
                event.target.parentNode.remove();
          });
        });
    });

        // Adiciona o listener para o botão de remover nos elementos iniciais
        const botoesRemoverIniciais = document.querySelectorAll('.remover-atributo');
        botoesRemoverIniciais.forEach(botao => {
          botao.addEventListener('click', (event) => {
                event.target.parentNode.remove();
          });
        });

</script>

<?php if (session()->getFlashdata('erro')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('erro') ?></div>
<?php endif; ?>