<!DOCTYPE html>
<html lang="pt-BR"><!--begin::Head-->

<head>
  <title>Conselhos | Dashboard</title><!--begin::Primary Meta Tags-->
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
              <h3 class="mb-0">Dashboard</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Dashboard
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

              <form method="post" id="form_cliente" name="form_cliente" action="<?= site_url('/clientes/adicionar') ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" class="form-control" value="">

                <div class="row">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" class="form-control">
                  </div>

                  <div class="form-group col-4">
                    <label>CPF / CNPJ</label>
                    <input type="number" name="cpf_cnpj" class="form-control">
                  </div>

                  <div class="form-group col-4">
                    <label>E-mail</label>
                    <input type="email" name="email" class="form-control">
                  </div>

                  <div class="form-group col-4">
                    <label>Celular</label>
                    <input type="text" name="celular" class="form-control">
                  </div>

                </div>
                <div class="row">
                  
                  <div class="form-group col-6">
                    <label>Logradouro</label>
                    <input type="text" name="logradouro" class="form-control">
                  </div>

                  <div class="form-group col-3">
                    <label>Número</label>
                    <input type="text" name="numero" class="form-control">
                  </div>

                  <div class="form-group col-3">
                    <label>Complemento</label>
                    <input type="text" name="complemento" class="form-control">
                  </div>

                </div>
                <div class="row">

                  <div class="form-group col-4">
                    <label>Bairro</label>
                    <input type="text" name="bairro" class="form-control">
                  </div>

                  <div class="form-group col-4">
                    <label>Cidade</label>
                    <input type="text" name="cidade" class="form-control">
                  </div>

                  <div class="form-group col-2">
                    <label>Estado</label>
                    <input type="text" name="estado" class="form-control">
                  </div>

                  <div class="form-group col-2">
                    <label>CEP</label>
                    <input type="text" name="cep" class="form-control">
                  </div>

                  <div class="form-group col-4">
                    <label>Data de Aquisição</label>
                    <input type="date" name="aquisicao_dt" class="form-control">
                  </div>

                </div>
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-primary">Salvar</button>
              </form>
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