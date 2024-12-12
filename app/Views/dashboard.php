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
    <?= $this->include('template/content') ?>

    <?= $this->include('template/modals/change_user_img.php') ?>

    <?= $this->include('template/footer') ?>
    </body><!--end::Body-->

</html>


