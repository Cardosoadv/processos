<!DOCTYPE html>
<html lang="pt-BR"><!--begin::Head-->

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
          <?= $this->include('template/componentes/breadcrumbs') ?>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div><!--end::App Content Header-->
      
      <!--begin::App Content-->
      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-9">
               
              <!-- Início do Formulário -->
              <form action="" method="get">
                <div class="input-group mb-3">
                  <input type="text" name="s" class="form-control" placeholder="Pesquisar..." aria-label="Pequisar" aria-describedby="button-addon2">
                  <button class="btn btn-outline-secondary" type="submit" id="search">Pesquisar</button>
                </div>
              </form>
              <div class="container mt-4">
                <div class="d-flex justify-content-end">
                  <a class="btn btn-success mb-2" href="<?php echo base_url('processos/novo/'); ?>">Novo Pocesso</a>
                </div>
                <?php
                if (isset($_SESSION['msg'])) {
                  echo '<div class="callout callout-info">';
                  echo $_SESSION['msg'];
                  echo '</div>';
                }
                ?>
                <div class="mt-3">
                  <?= $table ?>
                </div>
              </div>
            </div><!-- Fim do Formulário -->
            <div class="col-3"><!-- Inicio SideBar do Formulario -->
              <h1>Sidebar</h1>

            </div> <!-- Fim do SideBar do Formulario -->
          </div> <!-- Fim do Row -->


        </div><!--end::Container-->
      </div><!--end::App Content-->
    </main><!--end::App Main-->

    <?= $this->include('template/modals/change_user_img.php') ?>
    <?= $this->include('template/footer') ?>
</body><!--end::Body-->

</html>