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
                    <?= $this->include('componentes/breadcrumbs') ?>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Main Content Column -->
                        <div class="col-lg-9">
                            <!-- Search Form -->
                            <!-- Action Button and Messages -->
                            <div class="container">
                                <div class="d-flex justify-content-end mb-3">
                                    <a href="<?= base_url('financeiro/contas/novo/') ?>"
                                        class="btn btn-success">
                                        Nova conta
                                    </a>
                                </div>
                                <!-- Inicio da Notificação -->
                                <?= $this->include('componentes/notificacaoSessao') ?>



                                <!-- Data Table -->
                                <div class="mt-3">
                                    <?php if (empty($contas)): ?>
                                        <div class="alert alert-info">
                                            Nenhuma conta encontrada.
                                        </div>
                                    <?php else: ?>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Banco</th>
                                                    <th>Agência</th>
                                                    <th>Conta</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($contas as $conta): ?>
                                                    <tr>
                                                        <td><?= esc($conta['conta']) ?></td>
                                                        <td><?= esc($conta['banco']) ?></td>
                                                        <td><?= esc($conta['agencia']) ?></td>
                                                        <td><?= esc($conta['numero_conta']) ?></td>
                                                        <td>
                                                            <a href="<?= base_url('financeiro/contas/editar/' . $conta['id_conta']) ?>"
                                                                class="btn btn-sm btn-primary">
                                                                Editar
                                                            </a>
                                                            <a href="<?= base_url('financeiro/contas/excluir/' . $conta['id_conta']) ?>"
                                                                class="btn btn-sm btn-danger">
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

                        <!-- Sidebar -->
                        
                    </div>
                </div>
            </div>
        </main>

        <?= $this->include('template/modals/change_user_img.php') ?>

        <?= $this->include('template/footer') ?>
    </div>

</body>

</html>