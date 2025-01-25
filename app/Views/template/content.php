<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <?= $this->include('template/componentes/breadcrumbs') ?>
        </div><!--end::Container-->
    </div><!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <!--begin::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 1-->
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3><?= $qteProcessos ?></h3>
                            <p>Processos</p>
                        </div>
                        <div class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                            <i class="nav-icon bi bi-bank"></i>
                        </div>
                        <a href="<?=base_url('processos')?>" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Mais informações
                            <i class="bi bi-link-45deg"></i>
                        </a>
                    </div><!--end::Small Box Widget 1-->
                </div><!--end::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 2-->
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3><?= $qteClientes ?></h3>
                            <p>Clientes</p>
                        </div>
                        <div class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                        <i class="nav-icon bi bi-person"></i>
                        </div>
                        <a href="<?=base_url('clientes')?>" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Mais informações
                            <i class="bi bi-link-45deg"></i>
                        </a>
                    </div><!--end::Small Box Widget 2-->
                </div><!--end::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 3-->
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3><?= $qteTarefas ?></h3>
                            <p>Tarefas</p>
                        </div>
                        <div class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                        <i class="nav-icon bi bi-check2-square"></i>
                        </div>
                        <a href="<?=base_url('tarefas')?>" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Mais informações
                            <i class="bi bi-link-45deg"></i>
                        </a>
                    </div><!--end::Small Box Widget 3-->
                </div><!--end::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 4-->
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3>??</h3>
                            <p>Financeiro Em Construção</p>
                        </div>
                        <div class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                        <i class="nav-icon bi bi-cash"></i>
                        </div>
                        <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Mais informações
                            <i class="bi bi-link-45deg"></i>
                        </a>
                    </div><!--end::Small Box Widget 4-->
                </div><!--end::Col-->
            </div><!--end::Row-->
            </div><!-- /.row (main row) -->
        </div><!--end::Container-->
    </div><!--end::App Content-->
</main><!--end::App Main-->