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
                                
                                <?php if (isset($_SESSION['msg'])): ?>
                                    <div class="callout callout-info">
                                        <?= $_SESSION['msg'] ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Data Table -->
                                <div class="mt-3">
                                    <?php if (empty($intimacoes)): ?>
                                        <div class="alert alert-info">
                                            Nenhuma intimação encontrada.
                                        </div>
                                    <?php else: ?>
                                        
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Número do Processo</th>
                                                        <th scope="col">Tipo de Comunicação</th>
                                                        <th scope="col">Data de Disponibilização</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($intimacoes as $intimacao): ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?= base_url('processos/editarpornumerodeprocesso/' . $intimacao['numero_processo']) ?>">
                                                                <?= $intimacao['numero_processo'] ?>
                                                            </a>
                                                        </td>
                                                        <td><?= $intimacao['tipoComunicacao'] ?></td>
                                                        <td><?= date('d/m/Y', strtotime($intimacao['data_disponibilizacao']))  ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-3">
                            <section class="mb-4">
                                <h3>Últimos Processos Movimentados</h3>
                                <div id="processoMovimentados" class="list-group"></div>
                            </section>

                            <section>
                                <h3>Últimas Intimações</h3>
                                <div id="intimacoes" class="list-group"></div>
                            </section>
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
                container.innerHTML = Array.isArray(data) && data.length > 0
                    ? data.map(renderProcessItem).join('')
                    : '<div class="list-group-item">Nenhum processo encontrado</div>';
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
                container.innerHTML = Array.isArray(data) && data.length > 0
                    ? data.map(renderIntimacaoItem).join('')
                    : '<div class="list-group-item">Nenhuma intimação encontrada</div>';
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