<?php $uri = service('uri');
$active = $uri->getSegment(1);
?>
<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <?= $this->include('template/componentes/sidebar/brand') ?>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item"><a href="<?= site_url();?>" class="nav-link <?php echo(($active === !null) ? "active" : "");?>">
                    <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Home
                            
                        </p>
                    </a>
                </li>
                <?php if($permission['processos']):?>
                <li class="nav-item <?php echo (($active === "processos") ? "menu-open" : "");?>"><a href="<?php echo site_url('processos');?>" class="nav-link <?php echo (($active === "processos") ? "active" : "");?>">
                    <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Processos
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="./widgets/small-box.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Small Box</p>
                            </a></li>
                        <li class="nav-item"><a href="./widgets/info-box.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>info Box</p>
                            </a></li>
                        <li class="nav-item"><a href="./widgets/cards.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Cards</p>
                            </a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if($permission['intimacoes']):?>
                <li class="nav-item <?php echo (($active === "intimacoes") ? "menu-open" : "");?>"><a href="<?php echo site_url('intimacoes');?>" class="nav-link <?php echo (($active === "intimacoes") ? "active" : "");?>">
                    <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Intimacoes
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                </li>
                <?php endif; ?>


                
                <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            Layout Options
                            <span class="nav-badge badge text-bg-secondary me-3">6</span><i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="./layout/unfixed-sidebar.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Default Sidebar</p>
                            </a></li>
                        <li class="nav-item"><a href="./layout/fixed-sidebar.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Fixed Sidebar</p>
                            </a></li>
                        <li class="nav-item"><a href="./layout/fixed-complete.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Fixed Complete</p>
                            </a></li>
                        <li class="nav-item"><a href="./layout/sidebar-mini.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Sidebar Mini</p>
                            </a></li>
                        <li class="nav-item"><a href="./layout/collapsed-sidebar.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Sidebar Mini <small>+ Collapsed</small></p>
                            </a></li>
                        <li class="nav-item"><a href="./layout/logo-switch.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Sidebar Mini <small>+ Logo Switch</small></p>
                            </a></li>
                        <li class="nav-item"><a href="./layout/layout-rtl.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Layout RTL</p>
                            </a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon bi bi-tree-fill"></i>
                        <p>
                            UI Elements
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="./UI/general.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>General</p>
                            </a></li>
                        <li class="nav-item"><a href="./UI/timeline.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Timeline</p>
                            </a></li>
                    </ul>
                </li>

            </ul><!--end::Sidebar Menu-->
        </nav>
    </div><!--end::Sidebar Wrapper-->
</aside><!--end::Sidebar-->