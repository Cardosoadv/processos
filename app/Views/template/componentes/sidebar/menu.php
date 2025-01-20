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
        <a href="<?= site_url(); ?>" class="nav-link <?= ($active === null) ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Home</p>
        </a>
    </li>

    <?php if ($permission['clientes']): ?>
        <li class="nav-item <?= ($active === 'clientes') ? 'active' : ''; ?>">
            <a href="<?= site_url('clientes'); ?>" class="nav-link">
                <i class="nav-icon bi bi-person-gear"></i> <p>Clientes</p>
            </a>
        </li>
    <?php endif; ?>
    
    
    <?php if ($permission['processos']): ?>
        <li class="nav-item <?= ($active === 'processos') ? 'active' : ''; ?>">
            <a href="<?= site_url('processos'); ?>" class="nav-link">
                <i class="nav-icon bi bi-bank"></i> <p>Processos</p>
            </a>
        </li>
    <?php endif; ?>

    <?php if ($permission['intimacoes']): ?>
        <li class="nav-item <?= in_array($active, ['intimacoes', 'receberintimacoes', 'receberintimacoesjs']) ? 'menu-is-opening menu-open' : ''; ?>">
            <a href="<?= site_url('intimacoes'); ?>" class="nav-link <?= ($active === 'intimacoes') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-cloud-arrow-down"></i>
                <p>Intimacoes <i class="nav-arrow bi bi-chevron-right"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?= site_url('intimacoes/receberintimacoes'); ?>" class="nav-link <?= ($active === 'receberintimacoes') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-circle"></i><p>Receber Intimacoes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('intimacoes/receberintimacoesfabiano'); ?>" class="nav-link <?= ($active === 'receberintimacoes') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-circle"></i><p>Receber Intimacoes Fabiano</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('intimacoesjs'); ?>" class="nav-link <?= ($active === 'receberintimacoesjs') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-circle"></i><p>Receber Intimacoes JS</p>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?>

    <?php if ($permission['tarefas']): ?>
        <li class="nav-item <?= ($active === 'tarefas') ? 'active' : ''; ?>">
            <a href="<?= site_url('tarefas'); ?>" class="nav-link">
                <i class="nav-icon bi bi-check2-square"></i><p>Tarefas</p>
            </a>
        </li>
    <?php endif; ?>
</ul>