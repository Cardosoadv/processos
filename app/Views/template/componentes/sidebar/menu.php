<?php 
/**
 * Lógica necessária ao funcionamento do menu lateral
 */

use App\Libraries\Permissions;

$uri = service('uri');
$active = $uri->getSegment(1);

$permitions = new Permissions();
$permission = $permitions->permission();

log_message('debug', $active);

?>

<ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
    <li class="nav-item">
        <a href="<?= site_url(); ?>" class="nav-link <?= ($active === null) ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Home</p>
        </a>
    </li>

    <?php 
        /**
         * Checa permissão para exibir o menu de clientes
         */
        if ($permission['clientes']): ?>
            <li class="nav-item <?= ($active === 'clientes') ? 'active' : ''; ?>">
                <a href="<?= site_url('clientes'); ?>" class="nav-link">
                    <i class="nav-icon bi bi-person-gear"></i> <p>Clientes</p>
                </a>
            </li>
    <?php endif; ?>
    
    
    <?php
        /**
         * Checa permissão para exibir o menu de processos
         */
        if ($permission['processos']): ?>
            <li class="nav-item <?= ($active === 'processos') ? 'active' : ''; ?>">
                <a href="<?= site_url('processos'); ?>" class="nav-link">
                    <i class="nav-icon bi bi-bank"></i> <p>Processos</p>
                </a>
            </li>
    <?php endif; ?>

    <?php 
        /**
         * Checa permissão para exibir o menu de intimações
         */
        if ($permission['intimacoes']): ?>
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
                </ul>
            </li>
    <?php endif; ?>

    
    <?php 
        /**
         * Checa permissão para exibir o menu Financeiro
         */
        if ($permission['financeiro']): ?>
            <li class="nav-item <?= ($active == 'financeiro') ? 'active' : ''; ?>">
                <a href="<?= site_url('financeiro/index'); ?>" class="nav-link">
                    <i class="nav-icon bi bi-cash-coin"></i><p>Financeiro</p>
                </a>
                <ul>
                    <li class="nav-item <?= ($active == 'financeiro') ? 'active' : ''; ?>">
                        <a href="<?= site_url('financeiro/despesas'); ?>" class="nav-link">
                            <i class="nav-icon bi bi-cash-coin"></i><p>Despesas</p>
                        </a>
                    </li>
                    <li class="nav-item <?= ($active == 'financeiro') ? 'active' : ''; ?>">
                        <a href="<?= site_url('financeiro/pagamentoDespesas'); ?>" class="nav-link">
                            <i class="nav-icon bi bi-cash-coin"></i><p>Pagamentos</p>
                        </a>
                    </li>
                    <li class="nav-item <?= ($active == 'financeiro') ? 'active' : ''; ?>">
                        <a href="<?= site_url('financeiro/receitas'); ?>" class="nav-link">
                            <i class="nav-icon bi bi-cash-coin"></i><p>Receitas</p>
                        </a>
                    </li>
                    <li class="nav-item <?= ($active == 'financeiro') ? 'active' : ''; ?>">
                        <a href="<?= site_url('financeiro/pagamentoReceitas'); ?>" class="nav-link">
                            <i class="nav-icon bi bi-cash-coin"></i><p>Recebidos</p>
                        </a>
                    </li>
                    <li class="nav-item <?= ($active == 'financeiro') ? 'active' : ''; ?>">
                        <a href="<?= site_url('financeiro/contas'); ?>" class="nav-link">
                            <i class="nav-icon bi bi-cash-coin"></i><p>Contas</p>
                        </a>
                    </li>
                    <li class="nav-item <?= ($active == 'financeiro') ? 'active' : ''; ?>">
                        <a href="<?= site_url('financeiro/categorias'); ?>" class="nav-link">
                            <i class="nav-icon bi bi-cash-coin"></i><p>Categorias</p>
                        </a>
                    </li>
                </ul>
            </li>
    <?php endif; ?>

    <?php
        /**
         * Checa permissão para exibir o menu de tarefas
         */
        if ($permission['tarefas']): ?>
        <li class="nav-item <?= ($active === 'tarefas') ? 'active' : ''; ?>">
            <a href="<?= site_url('tarefas'); ?>" class="nav-link">
                <i class="nav-icon bi bi-check2-square"></i><p>Tarefas</p>
            </a>
        </li>
    <?php endif; ?>
</ul>