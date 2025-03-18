<li class="nav-item dropdown user-menu"><a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
    <img src="<?= base_url('usuarios/exibirFoto/').auth()->user()->id ?>" class="user-image rounded-circle shadow" alt="User Image">
    <span class="d-none d-md-inline"><?= auth()->user()->username ?></span></a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end"><!--begin::User Image-->
        <li class="user-header text-bg-primary">
        <a data-bs-toggle="modal" data-bs-target="#modal-alterar-foto-perfil">
            <img src="<?= base_url('usuarios/exibirFoto/').auth()->user()->id ?>" class="rounded-circle shadow" alt="User Image"  width="150" height="150">    
        </a>
            <p>
                <?= auth()->user()->username ?>
            </p>
        </li><!--end::User Image--><!--begin::Menu Body-->
        <li class="user-body"><!--begin::Row-->
            <div class="row">
                <div class="col-4 text-center"><a href="#">Followers</a></div>
                <div class="col-4 text-center"><a href="#">Sales</a></div>
                <div class="col-4 text-center"><a href="#">Friends</a></div>
            </div><!--end::Row-->
        </li><!--end::Menu Body--><!--begin::Menu Footer-->
        <li class="user-footer">
            <a href="#" class="btn btn-default btn-flat">
                Profile
            </a>
            <a href="<?= base_url('logout') ?>" class="btn btn-default btn-flat float-end">
                Sign out
            </a>
        </li><!--end::Menu Footer-->
    </ul>
</li>