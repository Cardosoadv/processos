<!--begin::Accordion-->
<div class="card card-primary card-outline mb-4"><!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Tarefas deste Processo</div>
        <div class="card-tools">
            <a data-bs-toggle="modal" data-bs-target="#modal-tarefa" id="openModalTarefa" class="btn btn-secondary"><i class="fas fa-plus"></i> Nova Tarefa</a>
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
                        <th>Tarefa</th>
                        <th>Prazo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tarefas as $tarefa): ?>
                        <tr>
                            <td><?= esc($tarefa['tarefa']) ?></td>
                            <td><?= esc(date('d/m/Y', strtotime($tarefa['prazo']))) ?></td>
                            <td><?= esc($tarefa['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div><!--end::Body-->