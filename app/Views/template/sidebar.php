
<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
        <?= $this->include('componentes/sidebar/brand') ?>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <!--begin::Sidebar Menu-->
                <?= $this->include('componentes/sidebar/menu') ?>
                <!--end::Sidebar Menu-->
            </nav>
        </div><!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->