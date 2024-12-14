<?php 
/**
 * Lógica necessária ao funcionamento do menu lateral
 */

use App\Libraries\Permissions;

$uri = service('uri');
$active = $uri->getSegment(1);

$permitions = new Permissions();
$permission = $permitions->permission();

?>

<ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
    <li class="nav-item">
        <a href="<?= site_url();?>" class="nav-link <?php echo(($active === !null) ? "active" : "");?>">
            <i class="nav-icon bi bi-speedometer"></i>
            <p> Home </p>
        </a>
    </li>

<?php if($permission['processos']):?>

    <li class="nav-item <?php echo (($active === "processos") ? "menu-open" : "");?>">
        <a href="<?php echo site_url('processos');?>" class="nav-link <?php echo (($active === "processos") ? "active" : "");?>">
            <i class="nav-icon bi bi-box-seam-fill"></i>
            <p> Processos <i class="nav-arrow bi bi-chevron-right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="./widgets/small-box.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                    <p>Small Box</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./widgets/info-box.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                    <p>info Box</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./widgets/cards.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                    <p>Cards</p>
                </a>
            </li>
        </ul>
    </li>

<?php endif; ?>

<?php if($permission['intimacoes']):?>

    <li class="nav-item <?php echo (($active === "intimacoes") ? "menu-open" : "");?>">
        <a href="<?php echo site_url('intimacoes');?>" class="nav-link <?php echo (($active === "intimacoes") ? "active" : "");?>"><i class="nav-icon bi bi-box-seam-fill"></i>
            <p>Intimacoes<i class="nav-arrow bi bi-chevron-right"></i></p>
        </a>
    </li>

<?php endif; ?>


                
    <li class="nav-item">
        <a href="#" class="nav-link"><i class="nav-icon bi bi-clipboard-fill"></i>
            <p>Layout Options<span class="nav-badge badge text-bg-secondary me-3">6</span><i class="nav-arrow bi bi-chevron-right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="./layout/unfixed-sidebar.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                    <p>Default Sidebar</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./layout/fixed-sidebar.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                    <p>Fixed Sidebar</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./layout/fixed-complete.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                    <p>Fixed Complete</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./layout/sidebar-mini.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                    <p>Sidebar Mini</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./layout/collapsed-sidebar.html" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                    <p>Sidebar Mini <small>+ Collapsed</small></p>
                </a>
            </li>
        </ul>
</ul>