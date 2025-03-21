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
                                                    <form id="formFiltraBalanco" class="mb-4">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label for="dataReferencia">Data de Referência</label>
                                                                <input type="date" name="dataReferencia" id="dataReferencia" class="form-control" value="<?= $resultado['data_referencia'] ?>">
                                                            </div>
                                                            <div class="col-md-2 d-flex align-items-end">
                                                                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <!-- Resumo Geral -->
                                                    <div class="row mb-4">
                                                        <div class="col-md-6">
                                                            <div class="card bg-light">
                                                                <div class="card-body">
                                                                    <h5>Saldo Total das Contas</h5>
                                                                    <h3 class="<?= $resultado['saldo_total'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                        R$ <?= number_format($resultado['saldo_total'], 2, ',', '.') ?>
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card bg-light">
                                                                <div class="card-body">
                                                                    <h5>Total de Rateio para Advogados</h5>
                                                                    <h3 class="<?= $resultado['rateio_total'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                        R$ <?= number_format($resultado['rateio_total'], 2, ',', '.') ?>
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <!-- Saldos por Conta -->
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h5>Saldos por Conta</h5>
                                                                </div>
                                                                <div class="card-body p-0">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-hover">
                                                                            <thead class="bg-light">
                                                                                <tr>
                                                                                    <th>Conta</th>
                                                                                    <th class="text-right">Saldo</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($resultado['saldos_contas'] as $conta_id => $dados): ?>
                                                                                    <tr>
                                                                                        <td><?= $dados['conta_nome'] ?></td>
                                                                                        <td class="text-right <?= $dados['saldo'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                                            R$ <?= number_format($dados['saldo'], 2, ',', '.') ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                            <tfoot>
                                                                                <tr class="font-weight-bold">
                                                                                    <td>TOTAL</td>
                                                                                    <td class="text-right <?= $resultado['saldo_total'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                                        R$ <?= number_format($resultado['saldo_total'], 2, ',', '.') ?>
                                                                                    </td>
                                                                                </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Rateios por Advogado -->
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h5>Valores de Rateio por Advogado</h5>
                                                                </div>
                                                                <div class="card-body p-0">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-hover">
                                                                            <thead class="bg-light">
                                                                                <tr>
                                                                                    <th>Advogado</th>
                                                                                    <th class="text-right">Valor de Rateio</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($resultado['rateios_advogados'] as $advogado_id => $dados): ?>
                                                                                    <tr>
                                                                                        <td><?= $dados['nome'] ?></td>
                                                                                        <td class="text-right <?= $dados['valor'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                                            R$ <?= number_format($dados['valor'], 2, ',', '.') ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                            <tfoot>
                                                                                <tr class="font-weight-bold">
                                                                                    <td>TOTAL</td>
                                                                                    <td class="text-right <?= $resultado['rateio_total'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                                        R$ <?= number_format($resultado['rateio_total'], 2, ',', '.') ?>
                                                                                    </td>
                                                                                </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Diferença entre Saldo e Rateio -->
                                                    <div class="row mt-4">
                                                        <div class="col-12">
                                                            <div class="alert <?= ($resultado['saldo_total'] - $resultado['rateio_total']) == 0 ? 'alert-success' : 'alert-warning' ?>" role="alert">
                                                                <h5>Diferença entre Saldo Total e Rateio Total</h5>
                                                                <h4>
                                                                    R$ <?= number_format($resultado['saldo_total'] - $resultado['rateio_total'], 2, ',', '.') ?>
                                                                    <?php if (($resultado['saldo_total'] - $resultado['rateio_total']) != 0): ?>
                                                                        <small><i class="fas fa-exclamation-triangle"></i> Este valor representa transações sem rateio ou com rateio incompleto.</small>
                                                                    <?php else: ?>
                                                                        <small><i class="fas fa-check-circle"></i> Os valores estão balanceados corretamente.</small>
                                                                    <?php endif; ?>
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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