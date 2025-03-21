<?php

$contas = model('Financeiro/FinanceiroContasModel')->orderBy('conta')->findAll();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<!--begin::Head-->

<head>
    <title><?= $titulo ?></title><!--begin::Primary Meta Tags-->
    <?= $this->include('template/header') ?>
</head><!--end::Head-->


<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <?= $this->include('template/nav') ?>
        <?= $this->include('template/sidebar') ?>

        <!--begin::App Main-->
        <main class="app-main">

            <!--begin::App Content Header-->
            <div class="app-content-header">

                <!--begin::Container-->
                <div class="container-fluid">

                    <!--begin::Row-->
                    <?= $this->include('componentes/breadcrumbs') ?>
                    <!--end::Row-->

                </div><!--end::Container-->
            </div><!--end::App Content Header-->

            <!--begin::App Content-->
            <div class="app-content">

                <!--begin::Container-->
                <div class="container-fluid">

                    <!-- Action Button and Messages -->
                    <div class="container">

                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-8">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h3 class="card-title"><?= $titulo ?></h3>
                                                </div>
                                                <div class="card-body">
                                                    <!-- Filtros -->
                                                    <form id="formFiltraExtrato" class="mb-4">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label for="conta_id">Conta</label>
                                                                <select name="conta_id" id="conta_id" class="form-control">
                                                                    <option value="0">Selecione uma conta</option>
                                                                    <?php if (!empty($contas)): ?>
                                                                        <?php foreach ($contas as $conta): ?>
                                                                            <option value="<?= $conta['id_conta'] ?? '' ?>" <?= $conta['id_conta'] == ($conta_id ?? '') ? 'selected' : '' ?>><?= $conta['conta'] ?></option>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="dataInicial">Data Inicial</label>
                                                                <input type="date" name="dataInicial" id="dataInicial" class="form-control" value="<?= $dataInicial ?>">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="dataFinal">Data Final</label>
                                                                <input type="date" name="dataFinal" id="dataFinal" class="form-control" value="<?= $dataFinal ?>">
                                                            </div>
                                                            <div class="col-md-2 d-flex align-items-end">
                                                                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <!-- Resumo do Saldo -->
                                                    <div class="row mb-4">
                                                        <div class="col-md-4">
                                                            <div class="card bg-light">
                                                                <div class="card-body">
                                                                    <h5>Saldo Anterior</h5>
                                                                    <h3 class="<?= $extrato['saldo_anterior_total'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                        R$ <?= number_format($extrato['saldo_anterior_total'], 2, ',', '.') ?>
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card bg-light">
                                                                <div class="card-body">
                                                                    <h5>Movimentação</h5>
                                                                    <h3 class="<?= ($extrato['saldo_final'] - $extrato['saldo_anterior_total']) >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                        R$ <?= number_format($extrato['saldo_final'] - $extrato['saldo_anterior_total'], 2, ',', '.') ?>
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card bg-light">
                                                                <div class="card-body">
                                                                    <h5>Saldo Final</h5>
                                                                    <h3 class="<?= $extrato['saldo_final'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                        R$ <?= number_format($extrato['saldo_final'], 2, ',', '.') ?>
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Detalhamento de Rateio Anterior -->
                                                    <div class="card mb-4">
                                                        <div class="card-header">
                                                            <h5>Detalhamento do Saldo Anterior por Rateio</h5>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th>Identificação</th>
                                                                            <th class="text-right">Valor</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($extrato['saldo_anterior'] as $id => $valor): ?>
                                                                            <tr>
                                                                                <td><?= $id === 'geral' ? 'Geral (Sem Rateio)' : getUserName($id) ?></td>
                                                                                <td class="text-right <?= $valor >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                                    R$ <?= number_format($valor, 2, ',', '.') ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Tabela de Extrato -->
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Data</th>
                                                                    <th>Descrição</th>
                                                                    <th class="text-right">Valor</th>
                                                                    <th class="text-right">Saldo</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($extrato['transacoes'] as $transacao): ?>
                                                                    <tr>
                                                                        <td><?= date('d/m/Y', strtotime($transacao['data'])) ?></td>
                                                                        <td><?= $transacao['descricao'] ?></td>
                                                                        <td class="text-right <?= $transacao['valor'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                            R$ <?= number_format($transacao['valor'], 2, ',', '.') ?>
                                                                        </td>
                                                                        <td class="text-right <?= $transacao['saldo'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                            R$ <?= number_format($transacao['saldo'], 2, ',', '.') ?>
                                                                        </td>
                                                                        
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- Resumo do Rateio Final -->
                                                    <div class="card mt-4">
                                                        <div class="card-header">
                                                            <h5>Resumo do Rateio Final</h5>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th>Identificação</th>
                                                                            <th class="text-right">Valor</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($extrato['rateio_acumulado'] as $id => $dados): ?>
                                                                            <tr>
                                                                                <td><?= $id === 'geral' ? 'Geral (Sem Rateio)' : getUserName($id) ?></td>
                                                                                <td class="text-right <?= $dados['rateio_acumulado'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                                    R$ <?= number_format($dados['rateio_acumulado'], 2, ',', '.') ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        
                                        // Form de filtro de extrato
                                        $('#formFiltraExtrato').on('submit', function(e) {
                                            e.preventDefault();
                                            var conta_id = $('#conta_id').val();
                                            var dataInicial = $('#dataInicial').val();
                                            var dataFinal = $('#dataFinal').val();

                                            if (conta_id && dataInicial && dataFinal) {
                                                window.location.href = '<?= site_url('financeiro/extrato/') ?>' + conta_id + '/' + dataInicial + '/' + dataFinal;
                                            } else {
                                                alert('Por favor, preencha todos os campos de filtro');
                                            }
                                        });
                                    });
                                </script>

                            </div>
                            <div class="col-4">
                                <!-- Inicio SideBar do Formulario -->
                                <!-- Inicio das Anotações -->
                                <div>
                                    <!-- Acordion -->
                                </div>
                                <!--end::Accordion-->
                                <!-- Fim das Anotações -->
                                <!-- Inicio das Intimações -->
                                <div>
                                    <!-- Intimações -->
                                </div>
                                <!--end::Accordion-->
                                <!-- Fim das Intimações -->

                                <!-- Inicio dos Movimentos -->
                                <div>
                                    <!-- Movimentos -->
                                </div>
                                <!--end::Accordion-->
                                <!-- Fim dos Movimentos -->
                            </div>
                        </div> <!-- Fim do SideBar do Formulario -->
                    </div> <!-- Fim do Row -->
                </div>
                <!-- Fim -->
            </div><!--end::Container-->
    </div><!--end::App Content-->
    </main><!--end::App Main-->
    <?= $this->include('template/modals/change_user_img.php') ?>

    <?= $this->include('template/footer') ?>
</body><!--end::Body-->

</html>