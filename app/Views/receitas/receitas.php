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
                                    <a href="<?= base_url('financeiro/receitas/novo/') ?>"
                                        class="btn btn-success">
                                        Nova receita
                                    </a>
                                </div>
                                <!-- Inicio da Notificação -->
                                <?= $this->include('componentes/notificacaoSessao') ?>



                                <!-- Data Table -->
                                <div class="mt-3">
                                    <?php if (empty($receitas)): ?>
                                        <div class="alert alert-info">
                                            Nenhum receita encontrado.
                                        </div>
                                    <?php else: ?>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Receita</th>
                                                    <th>Vencimento</th>
                                                    <th>Valor</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($receitas as $receita): ?>
                                                    <tr>
                                                        <td><?= esc($receita['receita']) ?></td>
                                                        <td><?= date('d/m/Y', strtotime($receita['vencimento_dt'])) ?></td>
                                                        <td><?= 'R$ ' . number_format($receita['valor'], 2, ',', '.') ?></td>
                                                        <td>
                                                            <a href="<?= base_url('financeiro/receitas/editar/' . $receita['id_receita']) ?>"
                                                                class="btn btn-sm btn-primary">
                                                                Editar
                                                            </a>
                                                            <a href="<?= base_url('financeiro/receitas/excluir/' . $receita['id_receita']) ?>"
                                                                class="btn btn-sm btn-danger">
                                                                Excluir
                                                            </a>
                                                            <a href="<?= base_url('financeiro/pagamentoReceitas/pagarReceita/' . $receita['id_receita']) ?>"
                                                                class="btn btn-sm btn-secondary">
                                                                Receber
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