<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title><?= $titulo ?></title>
    <?= $this->include('template/header') ?>
    <script>
        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev, ) {
            ev.dataTransfer.setData("text", ev.target.id);
        }

        function drop(ev, ui) {
            ev.preventDefault();
            var data = ev.dataTransfer.getData("text");
            ev.target.appendChild(document.getElementById(data));
            var status = ui;
            console.log(data, status);
            dEdit(data, status);
        }

        function dEdit(tarefa, status) {
            var xmlhttp = new XMLHttpRequest();
            var url = "<?php echo site_url('tarefas/a_editar_status') ?>?tarefa=" + tarefa + "&status=" + status;
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
        }
    </script>


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

            <div class="app-content kanban">
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

        <?= $this->include('template/modals/change_user_img.php') ?>
        <?= $this->include('template/footer') ?>
    </div>

    <script>
        const BASE_URL = '<?= base_url() ?>';

        // Utility functions
        const formatDate = timestamp => {
            const date = new Date(timestamp);
            return date.toLocaleDateString('pt-BR');
        };

        const createProcessLink = (numeroProcesso) => {
            return `${BASE_URL}/processos/editarpornumerodeprocesso/${numeroProcesso}`;
        };

        const handleFetchError = (error, elementId) => {
            console.error('Erro:', error);
            document.getElementById(elementId).innerHTML = `
                <div class="alert alert-danger">
                    Erro ao carregar informações. Tente novamente mais tarde.
                </div>
            `;
        };

        // Process data rendering
        const renderProcessItem = (item) => `
            <div class="list-group-item">
                <a href="${createProcessLink(item.numero_processo)}" class="text-primary">
                    ${item.numero_processo}
                </a>
                <p class="mb-1">${item.nome || 'Sem descrição'}</p>
                <small class="text-muted">Data: ${formatDate(item.dataHora)}</small>
            </div>
        `;

        const renderIntimacaoItem = (item) => `
            <div class="list-group-item">
                <a href="${createProcessLink(item.numero_processo)}" class="text-primary">
                    ${item.numero_processo}
                </a>
                <p class="mb-1">${item.tipoComunicacao || 'Sem descrição'}</p>
                <small class="text-muted">Data: ${formatDate(item.data_disponibilizacao)}</small>
            </div>
        `;

        // Data fetching functions
        async function fetchProcessos() {
            try {
                const response = await fetch(`${BASE_URL}/processos/processosmovimentados/30`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                const container = document.getElementById('processoMovimentados');
                container.innerHTML = Array.isArray(data) && data.length > 0 ?
                    data.map(renderProcessItem).join('') :
                    '<div class="list-group-item">Nenhum processo encontrado</div>';
            } catch (error) {
                handleFetchError(error, 'processoMovimentados');
            }
        }

        async function fetchIntimacoes() {
            try {
                const response = await fetch(`${BASE_URL}/intimacoes/intimacoesporperiodo/30`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                const container = document.getElementById('intimacoes');
                container.innerHTML = Array.isArray(data) && data.length > 0 ?
                    data.map(renderIntimacaoItem).join('') :
                    '<div class="list-group-item">Nenhuma intimação encontrada</div>';
            } catch (error) {
                handleFetchError(error, 'intimacoes');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            fetchProcessos();
            fetchIntimacoes();
        });
    </script>
</body>

</html>