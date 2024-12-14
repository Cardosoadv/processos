<!DOCTYPE html>
<html lang="pt-BR"><!--begin::Head-->

<head>
  <title>Conselhos | Consulta Processo</title><!--begin::Primary Meta Tags-->
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
              <h3 class="mb-0">Consulta Processo</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Consulta Processo
                </li>
              </ol>
            </div>
          </div><!--end::Row-->
        </div><!--end::Container-->
      </div><!--end::App Content Header--><!--begin::App Content-->
      <div class="app-content"><!--begin::Container-->
        <div class="container-fluid"><!--begin::Row-->
          <div class="row">
            <div class="col-8"> <!-- Início do Formulário -->
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
                  <form method="post" id="form_processo" name="form_processo" action="<?= site_url('/processos/atualizar/') . $processo['id_processo'] ?>" enctype="multipart/form-data">
                    <input type="hidden" name="id_processo" class="form-control" value="<?= $processo['id_processo'] ?>">
                    <div class="form-group col-5">
                      <label>Nome do Processo</label>
                      <input type="text" name="nome" class="form-control" value="<?= $processo['nome'] ?>">
                    </div>
                    <div class="form-group col-5">
                      <label>Ação</label>
                      <input type="text" name="acao" class="form-control" value="<?= $processo['acao'] ?>">
                    </div>
                    <div class="form-group">
                      <label>Numero do Processo</label>
                      <input type="text" id="numeroProcesso" name="numero" class="form-control" value="<?= $processo['numero'] ?>" onchange="mask(this)">
                    </div>
                    <div class="row mt-3">
                      <div class="form-group">
                        <label>Juízo</label>
                        <input type="text" name="juizo" class="form-control" value="<?= $processo['juizo'] ?>">
                      </div>
                      <div class="form-group col-3">
                        <label>Valor da Causa</label>
                        <input type="number" step="0.01" name="vlr_causa" class="form-control" value="<?= $processo['vlr_causa'] ?>">
                      </div>
                      <div class="form-group col-3">
                        <label>Data Distribuição</label>
                        <input type="date" name="dt_distribuicao" class="form-control" value="<?= $processo['dt_distribuicao'] ?>">
                      </div>
                      <div class="form-group col-3">
                        <label>Valor da Condenação</label>
                        <input type="number" step="0.01" name="vlr_condenacao" class="form-control" value="<?= $processo['vlr_condenacao'] ?>">
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
                    <a href="<?= site_url('/processos/') ?>" class="btn btn-outline-secondary right">Cancelar</a>
                  </div>
                </div>   
            </div><!-- Fim do Formulário -->
            <div class="col-4"><!-- Inicio SideBar do Formulario -->
              <div><!--begin::Accordion-->
                <div class="card card-primary card-outline mb-4"><!--begin::Header-->
                  <div class="card-header">
                    <div class="card-title">Anotações</div>
                    <div class="card-tools">
                      <a data-bs-toggle="modal" data-bs-target="#modal_anotacao" class="btn btn-secondary">Nova Anotação</a>
                    </div>
                  </div><!--end::Header-->

                      <!--begin::Body-->
                      <div class="card-body">
                        <div class="accordion" id="#Anotações">
                        <?php if ($anotacoes['anotacoes']) : ?>
                          <?php foreach ($anotacoes['anotacoes'] as $anotacao) : ?>
                          <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Anotacao<?= $anotacao['id_anotacao'] ?>" aria-expanded="false" aria-controls="Anotacao<?= $anotacao['id_anotacao'] ?>">
                                <?= $anotacao['titulo'] ?>
                              </button></h2>
                            <div id="Anotacao<?= $anotacao['id_anotacao'] ?>" class="accordion-collapse collapse" data-bs-parent="#<?= $anotacao['titulo'] ?>">
                              <div class="accordion-body"><?= $anotacao['anotacao'] ?><br><br>
                              Criada em <?= date('d/m/Y H:i', strtotime($anotacao['created_at'])) ?><br>
                              atualizada em <?php if(($anotacao['updated_at']) != NULL){
                              date('d/m/Y H:i', strtotime($anotacao['updated_at']));}else{echo 'Sem Atualização';} ?>
                              </div>
                            </div>
                          </div>
                          <?php endforeach; ?>
                          <?php endif; ?>
                        </div>
                      </div>
                </div><!--end::Body-->
              </div><!--end::Accordion-->
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