<?php //TODO: Corrigir o drag and drop do kanban 
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
                                <div class="card-body drop-area" data-id="Status-1">
                                    <?= $this->include('template/componentes/kamban/cartao') ?>
                                </div>
                            </div>
                            <div class="card card-row card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        A fazer
                                    </h3>
                                </div>
                                <div class="card-body drop-area" id="aFazer" data-id="Status-2">
                                </div>
                            </div>

                            <div class="card card-row card-default">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        Fazendo
                                    </h3>
                                </div>
                                <div class="card-body drop-area" data-id="Status-3">

                                </div>
                            </div>
                            <div class="card card-row card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Feito
                                    </h3>
                                </div>
                                <div class="card-body drop-area" data-id="Status-4">

                                </div>
                            </div>

                            <div class="card card-row card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Cancelados
                                    </h3>
                                </div>
                                <div class="card-body drop-area" data-id="Status-5">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?= $this->include('template/modals/tarefas.php') ?>
        <?= $this->include('template/modals/change_user_img.php') ?>
        <script>
            const draggables = document.querySelectorAll('.draggable');
            const dropAreas = document.querySelectorAll('.drop-area'); // Seleciona todas as áreas de drop

            draggables.forEach(draggable => {
                draggable.addEventListener('dragstart', () => {
                    draggable.classList.add('dragging');
                });

                draggable.addEventListener('dragend', () => {
                    draggable.classList.remove('dragging');
                });
            });

            dropAreas.forEach(dropArea => { // Itera sobre cada área de drop
                dropArea.addEventListener('dragover', e => {
                    e.preventDefault();
                    const afterElement = getDragAfterElement(dropArea, e.clientY);
                    const draggable = document.querySelector('.dragging');
                    if (draggable == null) return; // Sai se não houver elemento sendo arrastado
                    if (afterElement == null) {
                        dropArea.appendChild(draggable);
                    } else {
                        dropArea.insertBefore(draggable, afterElement);
                    }
                });

                dropArea.addEventListener('drop', e => {
                    e.preventDefault();
                    const draggable = document.querySelector('.dragging');
                    if (draggable == null) return; // Verifica se tem um elemento sendo arrastado
                });
            });

            function getDragAfterElement(dropArea, y) {
                const draggableElements = [...dropArea.querySelectorAll('.draggable:not(.dragging)')];

                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
                    if (offset < 0 && offset > closest.offset) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.NEGATIVE_INFINITY }).element;
            }
        </script>
        <?= $this->include('template/footer') ?>
    </div>
</body>

</html>