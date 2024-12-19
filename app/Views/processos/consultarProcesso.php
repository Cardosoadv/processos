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
              <?= $this->include('template/componentes/processos/formulario') ?>
              <!-- Fim do Formulário -->
            </div>
            <div class="col-4">
            <!-- Inicio SideBar do Formulario -->
            <div>
              <?= $this->include('template/componentes/processos/acordion') ?>
            </div>
            <!--end::Accordion-->
          </div> <!-- Fim do SideBar do Formulario -->
        </div> <!-- Fim do Row -->
      </div>
      <!-- Fim -->
  </div><!--end::Container-->
  </div><!--end::App Content-->
  </main><!--end::App Main-->
  <?= $this->include('template/modals/change_user_img.php') ?>
  <?= $this->include('template/modals/anotacao.php') ?>
  <?= $this->include('template/footer') ?>


  <script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js" crossorigin></script>
  <script src="<?= base_url('public/main.js')?>">
  </script>

		

</body><!--end::Body-->
<script>

  function mask(input) {
    var value = input.value.replace(/\D/g, '').substring(0, 20);
    const regex = /^(\d{7})(\d{2})(\d{4})(\d{1})(\d{2})(\d{4})$/;
    const maskPartes = regex.exec(value);
    if (!maskPartes) {
      console.log("NUP inválida");
    }
    const primeiraParte = maskPartes[1];
    const segundaParte = maskPartes[2];
    const terceiraParte = maskPartes[3];
    const quartaParte = maskPartes[4];
    const quintaParte = maskPartes[5];
    const sextaParte = maskPartes[6];
    var mask = primeiraParte + "-" + segundaParte + "." + terceiraParte + "." + quartaParte + "." + quintaParte + "." + sextaParte;
    input.value = mask;
  }
</script>



</html>