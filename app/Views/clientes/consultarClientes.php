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
          <?= $this->include('template/componentes/breadcrumbs') ?>
          <!--end::Row-->

        </div><!--end::Container-->
      </div><!--end::App Content Header-->

      <!--begin::App Content-->
      <div class="app-content">

        <!--begin::Container-->
        <div class="container-fluid">

          <!--begin::Row-->
          <div class="row">
            <div class="col-8">

              <!-- inicio formulário -->
              <?= $this->include('template/componentes/clientes/formulario') ?>
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


  <script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js" crossorigin></script>
  <script src="<?= base_url('public/js/main.js')?>">
  </script>

		

</body><!--end::Body-->

</html>