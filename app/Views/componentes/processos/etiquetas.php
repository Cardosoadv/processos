<div class="row mt-2">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="fw-bold me-2">Etiquetas:</span>
                <?php if($etiquetas ?? null) : ?>
                    <?php foreach ($etiquetas as $etiqueta) : ?>
                        <span class="badge" style="background-color:#<?= $etiqueta['cor'] ?>" id="<?= $etiqueta['id_etiqueta'] ?>"><?= $etiqueta['nome'] ?>&nbsp; 
                            <i class="fas fa-times"></i>
                        </span>
                    <?php endforeach; ?> 
                <?php endif; ?>
            <button class="btn btn-sm btn-outline-secondary" 
                data-bs-toggle="modal" 
                data-bs-target="#addTagModal">
                    <i class="fas fa-plus"></i> Adicionar
            </button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleciona todos os ícones de exclusão
        var deleteIcons = document.querySelectorAll('.badge i.fas.fa-times');
        var processo = <?= $processo['id_processo']?? 0?>;
        // Adiciona um ouvinte de evento de clique para cada ícone
        deleteIcons.forEach(function (icon) {
            icon.addEventListener('click', function () {
                // Encontra o elemento pai do ícone (a tag badge)
                var badge = this.parentElement;
                var etiquetaId = badge.id;

                // Faz a chamada AJAX para remover a etiqueta do banco de dados
                fetch('<?= site_url('processos/removeretiqueta') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id_processo: processo,
                        id_etiqueta: etiquetaId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        badge.remove(); // Remove a tag badge
                        mostrarMensagem('Etiqueta removida com sucesso!', 'success');
                    } else {
                        mostrarMensagem('Erro ao remover etiqueta.', 'error');;
                    }
                })
                .catch(error => {
                console.error('Erro:', error);
                mostrarMensagem('Erro ao remover etiqueta.', 'error');
            });
            });
        });
    });
</script>