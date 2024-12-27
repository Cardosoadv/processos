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
                            <?php foreach ($listaetiquetas as $listaetiqueta) : ?>
                                <?php if(in_array($listaetiqueta['id_etiqueta'], array_column($etiquetas, 'id_etiqueta'))) continue; ?>
                                <span class="badge" style="background-color:#<?= $listaetiqueta['cor'] ?>" id="<?= $listaetiqueta['id_etiqueta'] ?>" data-cor="#<?= $listaetiqueta['cor'] ?>" onclick="adcionaTag(this)">
                                    <?= $listaetiqueta['nome'] ?>
                                </span>  
                            <?php endforeach; ?>
                        </div>
                        <label for="tagName" class="form-label">Nome da Etiqueta</label>
                        <select name="tagName" id="tagName" class="form-select" required>
                            
                            <?php foreach ($listaetiquetas as $listaetiqueta) : ?>
                                <?php if(in_array($listaetiqueta['id_etiqueta'], array_column($etiquetas, 'id_etiqueta'))) continue; ?>
                                    <option value="<?= $listaetiqueta['id_etiqueta'] ?>">
                                        <span style="background-color:#<?= $listaetiqueta['cor'] ?>"><?= $listaetiqueta['nome'] ?></span>
                                    </option>
                                <php endif; ?>
                            <?php endforeach; ?>
                        </select>    
                    </div>
                    <div class="mb-3">
                        <label for="tagColor" class="form-label">Cor</label>
                        <input type="text" class="form-control" id="tagColor" required>
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
                newTag.className = `badge ${tagColor}`;
                newTag.textContent = tagName;
                
                // Inserir antes do botão de adicionar
                const addButton = tagsContainer.querySelector('.btn-outline-secondary');
                tagsContainer.insertBefore(newTag, addButton);
                
                // Fechar o modal e limpar o formulário
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTagModal'));
                modal.hide();
                document.getElementById('tagForm').reset();
            }
        }

        function adcionaTag(tag) {
            console.log(tag);
            const etiquetaId = parseInt(tag.id);
            const processoId = <?= $processo['id_processo']?>;
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
                        newTag.className = `badge`;
                        newTag.style.backgroundColor = tagColor; // Definir a cor da etiqueta
                        newTag.textContent = tagName;
            
                        // Inserir antes do botão de adicionar
                        const addButton = tagsContainer.querySelector('.btn-outline-secondary');
                        tagsContainer.insertBefore(newTag, addButton);
                    } else {
                        alert('Erro ao adicionar a etiqueta.');
                    }
                })
                .catch(error => console.error('Erro:', error));
            
            // Fechar o modal e limpar o formulário
            const modal = bootstrap.Modal.getInstance(document.getElementById('addTagModal'));
            modal.hide();
            document.getElementById('tagForm').reset();
            
        }


    </script>