<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <?= $this->include('componentes/breadcrumbs') ?>
        </div><!--end::Container-->
    </div><!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <!--begin::Col-->
                <div class="col-lg-6 col-9">
                    <!-- Inicio da Notificação -->
                    <?= $this->include('componentes/notificacaoSessao') ?>

                    <p class='mt-5 text-center'><strong>Você não tem permissão para acessar o módulo que você tentou acessar!</strong></p>

                </div><!--end::Col-->
            </div><!--end::Row-->
        </div><!--end::Container-->
    </div><!--end::App Content-->
</main><!--end::App Main-->