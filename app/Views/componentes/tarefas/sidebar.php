<!--begin::Accordion-->
<div class="card card-primary card-outline mb-4"><!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Tarefas deste Processo</div>
        <div class="card-tools">
            <a data-bs-toggle="modal" data-bs-target="#modal-tarefa" id="openModalTarefa" class="btn btn-secondary">
                <i class="fas fa-plus"></i> 
                Tarefa
            </a>
        </div>
    </div><!--end::Header-->

    <!-- Aqui ficará a lógica para exibir tarefas do processo -->
    <div class="mt-3">
        <?php if (empty($tarefas)): ?>
            <div class="alert alert-info">
                Nenhuma Tarefas!
            </div>
        <?php else: ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="col-5">Tarefa</th>
                        <th class="col">Prazo</th>
                        <th class="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tarefas as $tarefa): ?>
                        <tr>
                            <td><?= esc($tarefa['tarefa']) ?></td>
                            <td><?= esc(date('d/m/Y', strtotime($tarefa['prazo']))) ?></td>
                            <td>
                                <select name="status" id="status" class="form-control  status-select" data-tarefa-id=<?= esc($tarefa['id_tarefa']) ?> style="padding: 0.1rem 0.25rem; font-size: 0.8rem;">
                                    <option value="1" <?= ($tarefa['status'] == 1) ? 'selected' : ''; ?>>Backlog</option>
                                    <option value="2" <?= ($tarefa['status'] == 2) ? 'selected' : ''; ?>>A Fazer</option>
                                    <option value="3" <?= ($tarefa['status'] == 3) ? 'selected' : ''; ?>>Fazendo</option>
                                    <option value="4" <?= ($tarefa['status'] == 4) ? 'selected' : ''; ?>>Feito</option>
                                    <option value="5" <?= ($tarefa['status'] == 5) ? 'selected' : ''; ?>>Cancelado</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div><!--end::Body-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.status-select');

    selects.forEach(select => {
        select.addEventListener('change', function() {
            const tarefaId = this.dataset.tarefaId;
            const novoStatus = this.value;
            const statusId = this.dataset.statusId;
            const url = `<?=base_url("tarefas/editarstatus")?>?Tarefa-id=${tarefaId}&status-id=${novoStatus}`;
            fetch(url) // Requisição GET, sem necessidade de configurar headers ou body
            .then(response => {
                            if (response.ok) {
                                return response.json();
                            } else {
                                throw new Error('Erro na requisição AJAX'.response.message);
                            }
                        })
            .then(data => {
                            // Tratar a resposta, se necessário
                            console.log('Resposta do servidor:', data);
                            // Exibir mensagem de sucesso ou erro para o usuário
                            if (data.success) {
                                toastr.success("Tarefa movida com sucesso!");
                            } else {
                                toastr.error("Erro ao mover a tarefa.");
                            }
                        })
            .catch(error => {
                console.error('Erro:', error);
                alert("Erro ao atualizar o status");
            });
        });
    });
});
</script>