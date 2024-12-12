<!DOCTYPE html>
<html lang="pt-BR"><!--begin::Head-->
<head>
<title>Conselhos | Contas</title><!--begin::Primary Meta Tags-->    
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
                    <h3 class="mb-0">Contas</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Contas
                        </li>
                    </ol>
                </div>
            </div><!--end::Row-->
        </div><!--end::Container-->
    </div><!--end::App Content Header--><!--begin::App Content-->
    <div class="app-content"><!--begin::Container-->
        <div class="container-fluid"><!--begin::Row-->

        <!-- Início -->

        <form action="" method="get">    <div class="input-group mb-3">
        <input type="text" name="s" class="form-control" placeholder="Pesquisar..." aria-label="Pequisar" aria-describedby="button-addon2">
        <button class="btn btn-outline-secondary" type="submit" id="search">Pesquisar</button>
        </div></form> 

        <div class="container mt-4">
          <div class="d-flex justify-content-end">
            <a class="btn btn-success mb-2" href="<?php echo base_url('contas/novo/'); ?>">Novo Conta</a>
          </div>
          <?php
          if (isset($_SESSION['msg'])) {
            echo '<div class="callout callout-info">';
            echo $_SESSION['msg'];
            echo '</div>';
          }
          ?>
          <div class="mt-3">
            <table class="table table-bordered" id="clientes-list">
              <thead>
                <tr>
                  <th>Conta</th>
                  <th>Comentario</th>
                  <th>Ação</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($contas) : ?>
                  <?php foreach ($contas as $conta) : ?>
                    <tr>
                      <td><?php echo $conta['conta']; ?></td>
                      <td><?php echo $conta['comentario']; ?></td>
                      <td>
                        <a class="btn btn-primary btn-sm" href="<?php echo base_url('contas/consultar/' . $conta['id_conta']); ?>">Editar</a>
                        <a href="<?php echo base_url('contas/delete/' . $conta['id_conta']); ?>" class="btn btn-danger btn-sm">Deletar</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Fim -->
            
        </div><!--end::Container-->
    </div><!--end::App Content-->
</main><!--end::App Main-->

    <?= $this->include('template/modals/change_user_img.php') ?>
    <?= $this->include('template/footer') ?>
    </body><!--end::Body-->

</html>
