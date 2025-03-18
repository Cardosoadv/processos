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
            <?php echo '<pre>';
            print_r($data);
            echo '</pre>'; ?>
            
        </div><!--end::Container-->
    </div><!--end::App Content-->
</main><!--end::App Main-->

    <?= $this->include('template/modals/change_user_img.php') ?>

    <?= $this->include('template/footer') ?>
    </body><!--end::Body-->

</html>


