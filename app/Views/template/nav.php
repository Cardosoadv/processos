<nav class="app-header navbar navbar-expand bg-body"><!--begin::Container-->
    <div class="container-fluid"><!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
        </ul>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto"><!--begin::Navbar Search-->
            <li class="nav-item"><a class="nav-link" data-widget="navbar-search" href="#" role="button"><i class="bi bi-search"></i></a></li><!--end::Navbar Search-->
            <!--begin::Messages Dropdown Menu-->
            <?= $this->include('componentes/nav/messagesDropdown') ?>
            <!--end::Messages Dropdown Menu-->

            <!--begin::Notifications Dropdown Menu-->
            <?= $this->include('componentes/nav/notificationDropdown') ?>
            <!--end::Notifications Dropdown Menu-->

            <!--begin::Fullscreen Toggle-->
            <li class="nav-item"><a class="nav-link" href="#" data-lte-toggle="fullscreen"><i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i><i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i></a></li>
            <!--end::Fullscreen Toggle-->

            <!--begin::User Menu Dropdown-->
            <?= $this->include('componentes/nav/userMenuDropdown') ?>
            <!--end::User Menu Dropdown-->

        </ul>
        <!--end::End Navbar Links-->
    </div><!--end::Container-->
</nav><!--end::Header-->