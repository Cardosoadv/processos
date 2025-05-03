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
                            
                        <div class="container mt-4">
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><?= $titulo ?></h4>
            <div>
                <a href="<?= site_url('objetos') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <a href="<?= site_url('objetos/exportar?format=csv') ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-csv"></i> Exportar CSV
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <?php if (empty($objetos)): ?>
                <div class="alert alert-info">
                    Nenhum objeto encontrado.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tabela-objetos">
                        <thead>
                            <tr>
                                <?php foreach (array_keys($objetos[0]) as $coluna): ?>
                                <th><?= esc(ucfirst(str_replace('_', ' ', $coluna))) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($objetos as $objeto): ?>
                            <tr>
                                <?php foreach ($objeto as $valor): ?>
                                <td><?= esc($valor) ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tabela-objetos').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
            },
            "responsive": true,
            "order": [],
            "pageLength": 25
        });
    });
</script>

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