<?php 
$listaetiquetas = model('EtiquetasModel')->findAll();
?>

<script src="<?= base_url('public/js/jscolor.js')?>"></script>
<!-- Modal para adicionar nova etiqueta -->
<div class="modal fade" id="addTagModal" tabindex="-1" aria-labelledby="addTagModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTagModalLabel">Adicionar Etiqueta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="tagForm">
                    <div class="mb-3">
                        <div class="opcoes">
                            <h3 class="mb-3">Selecione uma etiqueta</h3>
                            <?php foreach ($listaetiquetas as $listaetiqueta) : ?>
                                <?php if(in_array($listaetiqueta['id_etiqueta'], array_column($etiquetas, 'id_etiqueta'))) continue; ?>
                                <span class="badge" style="background-color:#<?= $listaetiqueta['cor'] ?>" id="<?= $listaetiqueta['id_etiqueta'] ?>" data-cor="#<?= $listaetiqueta['cor'] ?>" onclick="adcionaTag(this)">
                                    <?= $listaetiqueta['nome'] ?>
                                </span>  
                            <?php endforeach; ?>
                        </div>
                        <h3 class="py-3">Crie uma nova etiqueta</h3>
                        <label for="tagName" class="form-label">Nome da Etiqueta</label>
                        <Input type="text" maxlength="20" name="tagName" id="tagName" class="form-control" required>   
                    </div>
                    <div class="mb-3">
                        <label for="tagColor" class="form-label">Cor</label>
                        <input type="text" class="form-control" data-jscolor="{}" id="tagColor" value="#C3C3C3" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="addNewTag()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
        // Adicione este código junto com o JavaScript existente
        function addNewTag() {
            const tagName = document.getElementById('tagName').value;
            const tagColor = document.getElementById('tagColor').value;
            
            if (tagName) {
                const tagsContainer = document.querySelector('.d-flex.flex-wrap.gap-2');
                const newTag = document.createElement('span');

                // Faz a chamada AJAX para adicionar a etiqueta do banco de dados
                fetch('<?= site_url('etiquetas/adicionar') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        nome: tagName,
                        cor: tagColor,
                        id_processo: <?= $processo['id_processo'] ?? 0 ?>,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const newTag = document.createElement('span');
                        const iconClosec = document.createElement('i');
                        iconClosec.className = 'fas fa-times';
                        newTag.className = `badge`;
                        newTag.style.backgroundColor = tagColor; // Definir a cor da etiqueta
                        newTag.textContent = tagName+' ';
                        newTag.appendChild(iconClosec);
            
                        // Inserir antes do botão de adicionar
                        const addButton = tagsContainer.querySelector('.btn-outline-secondary');
                        tagsContainer.insertBefore(newTag, addButton);
                        mostrarMensagem('Etiqueta adicionada com sucesso!', 'success');
                    } else {
                        mostrarMensagem('Erro ao adicionar etiqueta.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error)
                    mostrarMensagem('Erro ao remover etiqueta.', 'error');
                });

                // Código anterior para revisar
                newTag.className = `badge ${tagColor}`;
                newTag.textContent = tagName;
                
                // Inserir antes do botão de adicionar
                const addButton = tagsContainer.querySelector('.btn-outline-secondary');
                tagsContainer.insertBefore(newTag, addButton);
                mostrarMensagem('Etiqueta adicionada com sucesso!');
                
                // Fechar o modal e limpar o formulário
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTagModal'));
                modal.hide();
                document.getElementById('tagForm').reset();
            }
        }

        function adcionaTag(tag) {
            console.log(tag);
            const etiquetaId = parseInt(tag.id);
            const processoId = <?= $processo['id_processo'] ?? 0?>;
            const tagName = tag.textContent;
            const tagColor = tag.getAttribute('data-cor'); // Corrigir para obter a cor correta
            const tagsContainer = document.querySelector('.d-flex.flex-wrap.gap-2');
            
            // Faz a chamada AJAX para adicionar a etiqueta do banco de dados
            fetch('<?= site_url('processos/adicionaretiqueta') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id_processo: processoId,
                        id_etiqueta: etiquetaId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const newTag = document.createElement('span');
                        const iconClosec = document.createElement('i');
                        iconClosec.className = 'fas fa-times';
                        newTag.className = `badge`;
                        newTag.style.backgroundColor = tagColor; // Definir a cor da etiqueta
                        newTag.textContent = tagName+' ';
                        newTag.appendChild(iconClosec);
            
                        // Inserir antes do botão de adicionar
                        const addButton = tagsContainer.querySelector('.btn-outline-secondary');
                        tagsContainer.insertBefore(newTag, addButton);
                        mostrarMensagem('Etiqueta adicionada com sucesso!', 'success');
                    } else {
                        mostrarMensagem('Erro ao remover etiqueta.', 'error');
                    }
                })
                .catch(error => {console.error('Erro:', error);
                    mostrarMensagem('Erro ao adicionar etiqueta.', 'error');
                });
            
            // Fechar o modal e limpar o formulário   
            const modal = bootstrap.Modal.getInstance(document.getElementById('addTagModal'));
            modal.hide();
            document.getElementById('tagForm').reset();
            tag.remove();
        }


    </script>
    <script>
// Here we can adjust defaults for all color pickers on page:
jscolor.presets.default = {
    position: 'right',
    palette: [
        '#000000', '#7d7d7d', '#870014', '#ec1c23', '#ff7e26',
        '#fef100', '#22b14b', '#00a1e7', '#3f47cc', '#a349a4',
        '#ffffff', '#c3c3c3', '#b87957', '#feaec9', '#ffc80d',
        '#eee3af', '#b5e61d', '#99d9ea', '#7092be', '#c8bfe7',
    ],
    //paletteCols: 12,
    //hideOnPaletteClick: true,
};
</script>