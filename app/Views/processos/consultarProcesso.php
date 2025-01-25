<?php

$selected = $processo['id_processo'] ?? "";

?>

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

          <!-- Nova seção de etiquetas -->
          <?= $this->include('template/componentes/processos/etiquetas') ?>
          <!-- Fim da seção de etiquetas -->

        </div><!--end::Container-->
      </div><!--end::App Content Header-->

      <!--begin::App Content-->
      <div class="app-content">

        <!--begin::Container-->
        <div class="container-fluid">

          <!--begin::Row-->
          <div class="row">
            <div class="col-8">
              <?php if (null !== (session()->get('msg'))
                || (session()->get('success')) 
                || (session()->get('errors'))): ?>
                  <div class="callout <?= session()->has('errors') ? 'callout-danger' : 'callout-info' ?>">
                    <?php if (session()->has('msg')): ?>
                        <p><?= esc(session('msg')) ?></p>
                    <?php endif; ?>

                    <?php if (session()->has('success')): ?>
                        <p><?= esc(session('success')) ?></p>
                    <?php endif; ?>

                    <?php if (session()->has('errors')): ?>
                        <?php if (is_array(session('errors'))): ?>
                            <ul>
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p><?= esc(session('errors')) ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
              <?php endif; ?>
              <!-- inicio formulário -->
              <?= $this->include('template/componentes/processos/formulario') ?>
              <!-- Fim do Formulário -->
            </div>
            <div class="col-4">
              <!-- Inicio SideBar do Formulario -->

              <!-- Inicio das Tarefas -->
              <div>
                <?= $this->include('template/componentes/tarefas/sidebar') ?>
              </div>
              <!-- Fim das Tarefas -->

              <!-- Inicio das Anotações -->
              <div>
                <?= $this->include('template/componentes/processos/acordion') ?>
              </div>
              <!-- Fim das Anotações -->
              <!-- Inicio dos Movimentos -->
              <div>
                <?= $this->include('template/componentes/processos/movimentos') ?>
              </div>
              <!-- Fim dos Movimentos -->
              <!-- Inicio das Intimações -->
              <div>
                <?= $this->include('template/componentes/processos/intimacoes') ?>
              </div>
              <!-- Fim das Intimações -->
              <!-- Inicio das Imóveis -->
              <div>
              <?= $this->include('template/componentes/processos/objeto') ?>
              </div>
              <!-- Fim dos Imoveis -->

            </div>
          </div> <!-- Fim do SideBar do Formulario -->
        </div> <!-- Fim do Row -->
      </div>
      <!-- Fim -->
  </div><!--end::Container-->
  </div><!--end::App Content-->
  </main><!--end::App Main-->
  <?= $this->include('template/modals/change_user_img.php') ?>
  <?= $this->include('template/modals/anotacao.php') ?>
  <?= $this->include('template/modals/badge.php') ?>
  <?= $this->include('template/modals/tarefas.php') ?>
  <?= $this->include('template/modals/movimento.php') ?>
  <?= $this->include('template/modals/objeto.php') ?>
  <?= $this->include('template/footer') ?>


  <script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js" crossorigin></script>
  <script src="<?= base_url('public/js/main.js') ?>">
  </script>



</body><!--end::Body-->

</html>