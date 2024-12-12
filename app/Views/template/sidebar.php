<?php

use App\Models\SystemModel;

$systemModel = new SystemModel();
$menu = $systemModel->findAll();
?>

<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"><!--begin::Sidebar Brand-->
            <div class="sidebar-brand"><!--begin::Brand Link--><a href="./index.html" class="brand-link"><!--begin::Brand Image--><img src="<?= base_url('public/dist/assets/img/AdminLTELogo.png')?>" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"><!--end::Brand Image--><!--begin::Brand Text--><span class="brand-text fw-light">AdminLTE 4</span><!--end::Brand Text--></a><!--end::Brand Link--></div><!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2"><!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                    <?php if (isset($menu)) : ?>
                    <?php foreach ($menu as $item) : ?>
                    <!-- Inicio do Menu -->   
                    <li class="nav-item <?php if($item['menu']=="Pessoas"){echo "menu-open";}else{echo "menu-close";};?>"><a href="#" class="nav-link"><i class="nav-icon <?= $item['icone'] ?>"></i>
                                <p>
                                <?= $item['menu']."\n" ?>
                                <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <?php $submenu = $systemModel->submenu($item['id_menu'])?>
                            <?php if (isset($submenu)) : ?>                            
                                <ul class="nav nav-treeview" style="box-sizing: border-box; display: block;"><!-- inicio submenu -->
                            <?php foreach ($submenu as $subitem) : ?>

                                <li class="nav-item"><a href="<?= site_url($subitem['link']) ?>" class="nav-link"><i class="nav-icon <?= $subitem['icone'] ?>"></i>
                                        <p><?= $subitem['submenu']."\n" ?></p>
                                    </a></li>
                            <?php endforeach; ?>

                            <?php endif; ?>
                                </ul><!-- Fim submenu -->
                            
                        </li>

                        <?php endforeach; ?>
                <?php endif; ?>

                    </ul><!--end::Sidebar Menu-->
                </nav>
            </div><!--end::Sidebar Wrapper-->
        </aside><!--end::Sidebar-->