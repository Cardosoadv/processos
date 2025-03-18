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
                                <!-- Inicio da Notificação -->
                                <?= $this->include('componentes/notificacaoSessao') ?>

                                <div class="container">
                                    <?php if (session()->has('message')): ?>
                                        <div class="alert alert-success">
                                            <?= session('message') ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($errors)): ?>
                                        <div class="alert alert-danger">
                                            <?php foreach ($errors as $error): ?>
                                                <p><?= $error ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="card">
                                        <div class="card-body">
                                            <form action="<?= site_url('importacaoClientes/importar') ?>" method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="arquivo_txt">Selecione o arquivo TXT</label>
                                                    <input type="file" class="form-control" id="arquivo_txt" name="arquivo_txt" required>

                                                </div>
                                                <button type="submit" class="btn btn-primary mt-3">Importar</button>
                                            </form>
                                        </div>
                                    </div>
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