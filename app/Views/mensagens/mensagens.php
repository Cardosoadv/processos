<?php

function getRemetente($id)
{

    $userModel = model('ResposavelModel');
    $userName = $userModel->getUserName($id);

    return $userName;
}
?>

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
                            <form action="" method="get" class="mb-3">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        name="s"
                                        class="form-control"
                                        placeholder="Pesquisar..."
                                        aria-label="Pesquisar">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        Pesquisar
                                    </button>
                                </div>
                            </form>

                            <!-- Action Button and Messages -->
                            <div class="container">
                                <div class="d-flex justify-content-end mb-3">
                                    <a href="<?= base_url('mensagens/novo/') ?>"
                                        class="btn btn-success">
                                        Nova Mensagem
                                    </a>
                                </div>
                                <!-- Inicio da Notificação -->
                                <?= $this->include('componentes/notificacaoSessao') ?>

                                <!-- Data Table -->
                                <div class="mt-3">
                                    <?php if (empty($mensagens)): ?>
                                        <div class="alert alert-info">
                                            Nenhuma mensagem encontrada.
                                        </div>
                                    <?php else: ?>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Data</th>
                                                    <th>Assunto</th>
                                                    <th>Remetente</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($mensagens as $mensagem): ?>
                                                    <tr>

                                                        <?php if ($mensagem['data_leitura'] == null): ?>
                                                            <td><i class="bi bi-envelope"></i></td>
                                                        <?php else: ?>
                                                            <td><i class="bi bi-envelope-paper"></i></td>
                                                        <?php endif; ?>

                                                        <td><?= date('d/m/Y', strtotime($mensagem['data_envio'])) ?></td>
                                                        <td><?= $mensagem['assunto'] ?></td>
                                                        <td><?php echo (getRemetente($mensagem['remetente_id'])) ?></td>
                                                        <td>
                                                            <a href="<?= base_url('mensagens/ler/' . $mensagem['id']) ?>"
                                                                class="btn btn-sm btn-primary">
                                                                Ler
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