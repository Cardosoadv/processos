<li class="nav-item dropdown user-menu"><a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
    <img src="<?= base_url('public/dist/assets/img/user1-128x128.jpg') ?>" class="user-image rounded-circle shadow img-size-50" alt="User Image">
    <span class="d-none d-md-inline"><?= auth()->user()->username ?></span></a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <!--begin::User Image-->
        <li class="user-header text-bg-primary">
            <a data-bs-toggle="modal" data-bs-target="#modal-alterar-foto-perfil">

                <img src="<?= base_url('public/dist/assets/img/user1-128x128.jpg')  ?>" class="rounded-circle shadow img-size-50" alt="User Image"></a>
            <p>
                <?= auth()->user()->username ?>
            </p>
        </li><!--end::User Image-->
        <!--begin::Menu Footer-->
        <li class="user-footer"><a href="#" class="btn btn-default btn-flat">Profile</a><a href="<?= base_url('logout') ?>" class="btn btn-default btn-flat float-end">Sign out</a></li><!--end::Menu Footer-->
    </ul>
</li>