<div class="modal fade" id="modalEditarTarefa" tabindex="-1" role="dialog" aria-labelledby="modalEditarTarefaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarTarefaLabel">Editar Tarefa</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarTarefa" action="<?= base_url('tarefas/atualizar') ?>" method="post">
                    <input type="hidden" name="id_tarefa" id="edit_id_tarefa">
                    <div class="form-group">
                        <label for="edit_tarefa">Tarefa</label>
                        <input type="text" class="form-control" id="edit_tarefa" name="tarefa">
                    </div>

                    <div class="form-group">
                        <label for="edit_prazo">Prazo</label>
                        <input type="date" class="form-control" id="edit_prazo" name="prazo">
                    </div>

                    <div class="form-group py-1">
                        <label for="edit_detalhes">Detalhes</label>
                        <textarea class="form-control" id="edit_detalhes" name="detalhes"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary py-2">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-tarefa');
    const modal = new bootstrap.Modal(document.getElementById('modalEditarTarefa')); // Inicializa o modal do Bootstrap

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tarefaId = this.dataset.tarefaId;
            const tarefaNome = this.dataset.tarefaNome;
            const tarefaPrazo = this.dataset.tarefaPrazo;
            const tarefaDetalhes = this.dataset.tarefaDetalhes;


            document.getElementById('edit_id_tarefa').value = tarefaId;
            document.getElementById('edit_tarefa').value = tarefaNome;
            document.getElementById('edit_prazo').value = tarefaPrazo;
            document.getElementById('edit_detalhes').value = tarefaDetalhes;

            modal.show(); // Exibe o modal
        });
    });
});
</script>