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
            <!-- Inicio do Kanban -->
            <div class="app-content kanban">
                <div class="container mt-4">
                    <div class="d-flex justify-content-end">
                        <a data-bs-toggle="modal" data-bs-target="#modal-tarefa" id="openModalTarefa" class="btn btn-success mb-2">Nova Tarefa</a>
                    </div>
                </div>
                <div class="content">
                    <div class="container-fluid">
                        <div class="container-fluid h-100">
                            <div class="card card-row card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Backlog
                                    </h3>
                                </div>
                                <div class="card-body" ondrop="drop(event,1)" ondragover="allowDrop(event)">
                                    <?= $this->include('template/componentes/kamban/cartao') ?>
                                </div>
                            </div>
                            <div class="card card-row card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        A fazer
                                    </h3>
                                </div>
                                <div class="card-body" ondrop="drop(event,2)" ondragover="allowDrop(event)">
                                </div>
                            </div>

                            <div class="card card-row card-default">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        Fazendo
                                    </h3>
                                </div>
                                <div class="card-body" ondrop="drop(event,3)" ondragover="allowDrop(event)">

                                </div>
                            </div>
                            <div class="card card-row card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Feito
                                    </h3>
                                </div>
                                <div class="card-body" ondrop="drop(event, 4)" ondragover="allowDrop(event)">

                                </div>
                            </div>

                            <div class="card card-row card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Cancelados
                                    </h3>
                                </div>
                                <div class="card-body" ondrop="drop(event, 5)" ondragover="allowDrop(event)">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?= $this->include('template/modals/tarefas.php') ?>
        <?= $this->include('template/modals/change_user_img.php') ?>
        <?= $this->include('template/footer') ?>
    </div>
</body>

</html>