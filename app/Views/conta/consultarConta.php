<!DOCTYPE html>
<html lang="pt-BR"><!--begin::Head-->

<head>
  <title>Conselhos | Consultar Conta</title><!--begin::Primary Meta Tags-->
  <?= $this->include('template/header') ?>
</head><!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"><!--begin::App Wrapper-->
  <div class="app-wrapper">
    <?= $this->include('template/nav') ?>
    <?= $this->include('template/sidebar') ?>

    <!--begin::App Main-->
    <main class="app-main"><!--begin::App Content Header-->
      <div class="app-content-header"><!--begin::Container-->
        <div class="container-fluid"><!--begin::Row-->
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Consultar Conta</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                Consultar Conta
                </li>
              </ol>
            </div>
          </div><!--end::Row-->
        </div><!--end::Container-->
      </div><!--end::App Content Header--><!--begin::App Content-->
      <div class="app-content"><!--begin::Container-->
        <div class="container-fluid"><!--begin::Row-->

          <!-- Início -->
          <div class="container mt-4">

            <?php
            if (isset($_SESSION['msg'])) {
              echo '<div class="callout callout-info">';
              echo $_SESSION['msg'];
              echo '</div>';
            }
            ?>

            <div class="mt-3"><!-- inicio formulário -->

              <form method="post" id="form_conta" name="form_conta" action="<?= site_url('/contas/atualizar/').$conta['id_conta'] ?>" enctype="multipart/form-data">
                <input type="hidden" name="id_conta" class="form-control" value="<?= $conta['id_conta']?>">

                <div class="row">
                  <div class="form-group col-4">
                    <label>Conta</label>
                    <input type="text" name="conta" class="form-control" value="<?= $conta['conta']?>">
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="form-group col-4">
                    <label>Comentários</label>
                    <input type="text" name="comentario" class="form-control" value="<?= $conta['comentario']?>">
                  </div>
                </div>
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-primary">Atualizar</button>
              </form>
              <a href="<?= site_url('/contas/') ?>" class="btn btn-secondary right">Cancelar</a>
            </div><!-- fim formulário -->

          </div>
          <!-- Fim -->

        </div><!--end::Container-->
      </div><!--end::App Content-->
    </main><!--end::App Main-->

    <?= $this->include('template/modals/change_user_img.php') ?>
    <?= $this->include('template/footer') ?>
</body><!--end::Body-->

</html>