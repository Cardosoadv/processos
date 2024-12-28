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
                <p> Processos </p>
            </a>
        </li>

    <?php endif; ?>

    <?php if($permission['intimacoes']):?>

        <li class="nav-item <?php echo (($active === "intimacoes") ? "menu-open" : "");?>">
            <a href="<?php echo site_url('intimacoes');?>" class="nav-link <?php echo (($active === "intimacoes") ? "active" : "");?>"><i class="nav-icon bi bi-box-seam-fill"></i>
                <p>Intimacoes</p>
            </a>
        </li>

    <?php endif; ?>
</ul>