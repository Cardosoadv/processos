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
            <!-- Inicio do Conteeúddo -->
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
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
                                            <th>Tarefa</th>
                                            <th>Prazo</th>
                                            <th>Status</th>
                                            <th>Processo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tarefas as $tarefa): ?>
                                            <tr>
                                                <td><?= esc($tarefa['tarefa']) ?></td>
                                                <td><?= esc(date('d/m/Y', strtotime($tarefa['prazo']))) ?></td>
                                                <td><?= esc($tarefa['status']) ?></td>
                                                <td>
                                                    <a href="<?= base_url('processos/editar/' . $tarefa['processo_id']) ?>">
                                                        <?= esc($tarefa['processo_id']) ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('tarefas/editar/' . $tarefa['id_tarefa']) ?>" class="btn btn-sm btn-primary">
                                                        Editar
                                                    </a>
                                                    <a href="<?= base_url('tarefas/excluir/' . $tarefa['id_tarefa']) ?>" class="btn btn-sm btn-danger">
                                                        Excluir
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?= $pager->links() ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?= $this->include('template/modals/tarefas.php') ?>
        <?= $this->include('template/modals/change_user_img.php') ?>
    </div>
    <?= $this->include('template/footer') ?>
</body>

</html>