<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Conselhos | Dashboard</title>
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
                    <p>Próxima atualização em: <span id="countdown">5</span> segundos</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nº Processo</th>
                                <th>Arquivo JSON</th>
                                <th>Número de Movimentos</th>
                                <th>Movimentos Salvos</th>
                                <th>Movimentos Ignorados</th>
                                <th>Erro</th>
                                <th>Última Checagem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($table as $item): ?>
                                <tr>
                                    <td><?= esc($item['numero_processo'] ?? null) ?></td>
                                    <td><?= esc($item['data']['json_filename'] ?? null) ?></td>
                                    <td><?= esc($item['data']['numero_movimentos'] ?? null) ?></td>
                                    <td><?= esc($item['data']['movimentos_salvos'] ?? null) ?></td>
                                    <td><?= esc($item['data']['movimentos_ignorados'] ?? null) ?></td>
                                    <td><?= esc($item['data']['erro'] ?? null) ?></td>
                                    <td><?= date('d/m/Y H:i:s', strtotime($item['data']['ultima_checagem'] ?? null)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <?= $this->include('template/modals/change_user_img.php') ?>
        <?= $this->include('template/footer') ?>
    </div>

    <script>
        let countdownValue = 5;

        function updateCountdown() {
            document.getElementById('countdown').textContent = countdownValue;
            countdownValue--;

            if (countdownValue < 0) {
                location.reload();
            }
        }

        setInterval(updateCountdown, 1000); // Update every 1 second
    </script>
</body>
</html>