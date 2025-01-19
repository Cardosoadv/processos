<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title><?= $titulo ?></title>
    <?= $this->include('template/header') ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?= $this->include('template/nav') ?>
        <?= $this->include('template/sidebar') ?>

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <?= $this->include('template/componentes/breadcrumbs') ?>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Main Content Column -->
                        <div class="col-lg-9">
                            <div class="container">
                                <div class="d-flex justify-content-end mb-3">
                                <a href="<?= base_url("tarefas")?>" class="btn btn-secondary mx-2">Exibir Kamban</a>


                                    <a href="<?= base_url('tarefas/novo/') ?>" 
                                        class="btn btn-success">
                                        Nova Tarefa
                                    </a>
                                </div>

                                <?php if (isset($_SESSION['msg'])): ?>
                                    <div class="callout callout-info">
                                        <?= session()->get('msg') ?>
                                        <?= session()->get('errors') ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Data Table -->
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
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-3">
                            <section class="mb-4">
                                <h3>Últimos Processos Movimentados</h3>
                                <div id="processoMovimentados" class="list-group"></div>
                            </section>

                            <section>
                                <h3>Últimas Intimações</h3>
                                <div id="intimacoes" class="list-group"></div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?= $this->include('template/modals/change_user_img.php') ?>
        
        <?= $this->include('template/footer') ?>
    </div>


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

</body>
</html>