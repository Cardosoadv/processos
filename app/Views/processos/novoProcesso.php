<!DOCTYPE html>
<html lang="pt-BR"><!--begin::Head-->

<head>
  <title>Conselhos | Novo Processo</title><!--begin::Primary Meta Tags-->
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
              <h3 class="mb-0">Novo Processo</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Novo Processo
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

              <form method="post" id="form_processo" name="form_processo" action="<?= site_url('/processos/adicionar') ?>" enctype="multipart/form-data">
                <input type="hidden" name="id_processo" class="form-control" value="">

                <div class="row">
                  <div class="form-group col-6">
                    <label>Nome do Processo</label>
                    <input type="text" name="nome" class="form-control">
                  </div>
                  <div class="form-group col-6">
                    <label>Ação</label>
                    <input type="text" name="acao" class="form-control">
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="form-group col-8">
                      <label>Cliente Principal</label>
                      <input type="text" name="cliente_principal" class="form-control">
                    </div>
                    <div class="form-group col-4">
                      <label>Qualificacao do Cliente</label>
                      <input type="text" name="cliente_qualificacao" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                  <div class="form-group col-8">
                      <label>Outra Parte</label>
                      <input type="text" name="outra_parte" class="form-control">
                    </div>
                    <div class="form-group col-4">
                      <label>Qualificacao do Cliente</label>
                      <input type="text" name="outraParte_qualificacao" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                  <label>Numero do Processo</label>
                  <input type="text" id="numeroProcesso" name="numero" class="form-control" onchange="mask(this)">
                </div>

                <div class="row mt-3">

                <div class="form-group">
                  <label>Juízo</label>
                  <input type="text" name="juizo" class="form-control">
                </div>

                  <div class="form-group col-3">
                    <label>Valor da Causa</label>
                    <input type="number" step="0.01" name="vlr_causa" class="form-control">
                  </div>

                  <div class="form-group col-3">
                    <label>Data Distribuição</label>
                    <input type="date" name="dt_distribuicao" class="form-control">
                  </div>

                  <div class="form-group col-3">
                    <label>Valor da Condenação</label>
                    <input type="number" step="0.01" name="vlr_condenacao" class="form-control">
                  </div>

                </div>
                <div class="row mt-3">

                  <div class="form-group">
                    <label>Comentários</label>
                    <textarea class="form-control" name="comentario" aria-label="Comentários"></textarea>
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