<?php

/**
 * Lógica necessária ao funcionamento do menu lateral
 */

use App\Libraries\Permissions;

$uri = service('uri');
$active = $uri->getSegment(1);
$subActive = $uri->getSegment(2) ?? null;

$permitions = new Permissions();
$permission = $permitions->permission();

log_message('debug', $active . ' / ' . $subActive);

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
            <a href="<?= site_url('clientes'); ?>" class="nav-link <?= ($active === 'clientes') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-person-gear"></i>
                <p>Clientes</p>
            </a>
        </li>
    <?php endif; ?>


    <?php
    /**
     * Checa permissão para exibir o menu de processos
     */
    if ($permission['processos']): ?>
        <li class="nav-item <?= ($active === 'processos') ? 'active' : ''; ?>">
            <a href="<?= site_url('processos'); ?>" class="nav-link <?= ($active === 'processos') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-bank"></i>
                <p>Processos</p>
            </a>
        </li>
    <?php endif; ?>

    <?php
    /**
     * Checa permissão para exibir o menu de intimações
     */
    if ($permission['intimacoes']): 
        $intimacoesSubItems = ['receberintimacoes', 'receberintimacoesfabiano'];
        $isIntimacaoActive = ($active === 'intimacoes' || in_array($subActive, $intimacoesSubItems));
    ?>
        <li class="nav-item <?= $isIntimacaoActive ? 'menu-is-opening menu-open' : ''; ?>">
            <a href="<?= site_url('intimacoes'); ?>" class="nav-link <?= ($active === 'intimacoes' && $subActive === null) ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-cloud-arrow-down"></i>
                <p>Intimacoes <i class="nav-arrow bi bi-chevron-right"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?= site_url('intimacoes/receberintimacoes'); ?>" class="nav-link <?= ($subActive === 'receberintimacoes') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Receber Intimacoes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('intimacoes/receberintimacoesfabiano'); ?>" class="nav-link <?= ($subActive === 'receberintimacoesfabiano') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Receber Intimacoes Fabiano</p>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?>


    <?php
    /**
     * Checa permissão para exibir o menu Financeiro
     */
    if ($permission['financeiro']):
        $financeiroSubItems = ['despesas', 'pagamentoDespesas', 'receitas', 'pagamentoReceitas', 'contas', 'categorias'];
        $isFinanceiroActive = ($active === 'financeiro' || in_array($subActive, $financeiroSubItems));
    ?>
        <li class="nav-item <?= $isFinanceiroActive ? 'menu-is-opening menu-open' : ''; ?>">
            <a href="<?= site_url('financeiro/index'); ?>" class="nav-link <?= ($active === 'financeiro' && $subActive === 'index') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-cash-coin"></i>
                <p>Financeiro <i class="nav-arrow bi bi-chevron-right"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?= site_url('financeiro/despesas'); ?>" class="nav-link <?= ($subActive === 'despesas') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-cash-coin"></i>
                        <p>Despesas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('financeiro/pagamentoDespesas'); ?>" class="nav-link <?= ($subActive === 'pagamentoDespesas') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-arrow-up-circle"></i>
                        <p>Pagamentos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('financeiro/receitas'); ?>" class="nav-link <?= ($subActive === 'receitas') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-cash-coin"></i>
                        <p>Receitas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('financeiro/pagamentoReceitas'); ?>" class="nav-link <?= ($subActive === 'pagamentoReceitas') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-arrow-down-circle"></i>
                        <p>Recebidos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('financeiro/contas'); ?>" class="nav-link <?= ($subActive === 'contas') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-bank"></i>
                        <p>Contas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('financeiro/categorias'); ?>" class="nav-link <?= ($subActive === 'categorias') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-tag"></i>
                        <p>Categorias</p>
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
            <a href="<?= site_url('tarefas'); ?>" class="nav-link <?= ($active === 'tarefas') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-check2-square"></i>
                <p>Tarefas</p>
            </a>
        </li>
    <?php endif; ?>
</ul>