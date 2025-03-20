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

                            <!-- Action Button and Messages -->
                            <div class="container">
                                <div class="d-flex justify-content-end mb-3">
                                    <a href="<?= base_url('mensagens/') ?>"
                                        class="btn btn-success">
                                        Mensagens
                                    </a>
                                </div>
                                <!-- Inicio da Notificação -->
                                <?= $this->include('componentes/notificacaoSessao') ?>
                                <!-- Data Table -->
                                <div class="mt-3">
                                    <?php if (empty($mensagem)): ?>
                                        <div class="alert alert-info">
                                            Nenhuma mensagem encontrada.
                                        </div>
                                    <?php else: ?>
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Assunto: <?= esc($mensagem['assunto']) ?></h4><br />
                                                <div class="card-options">
                                                    <label>Remetente: <?= esc(getUserName($mensagem['remetente_id'])) ?></label><br />
                                                    <label>Data de Envio: <?= date('d/m/Y H:i:s', strtotime($mensagem['data_envio'])) ?></label><br />
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p><?= esc($mensagem['conteudo']) ?></p>
                                            </div>
                                            <div class="card-footer">
                                                <p>Lido em: <?= $mensagem['data_leitura'] ? date('d/m/Y H:i:s', strtotime($mensagem['data_leitura'])) : 'Não lida' ?></p>
                                            </div>
                                        </div>
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