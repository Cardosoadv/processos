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
            <div class="d-flex justify-content-end mb-3">
                                    <a href="<?= base_url('financeiro/index?print=true') ?>"
                                        class="btn btn-success">
                                        Imprimir
                                    </a>
                                </div>
              <!-- Inicio da Notificação -->
              <?= $this->include('componentes/notificacaoSessao') ?>

              <!-- inicio Extrato -->
              <!-- Data Table -->
              <div class="mt-3">
                <?php if (empty($extrato)): ?>
                  <div class="alert alert-info">
                    Nenhum movimento encontrado.
                  </div>
                <?php else: ?>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Saldo</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($extrato as $registro): ?>
                        <tr>
                          <td><?= date('d/m/Y', strtotime($registro['data'])) ?></td>
                          <td><?= esc($registro['descricao']) ?></td>
                          <td>R$ <?= number_format($registro['valor'],2,'.',',') ?></td>
                          <td>R$ <?= number_format($registro['saldo'],2,'.',',') ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                <?php endif; ?>
                <!-- Fim do Formulário -->
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