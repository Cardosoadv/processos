
<li class="nav-item dropdown">
    <a class="nav-link" data-bs-toggle="dropdown" href="#">
        <i class="bi bi-bell-fill"></i>
        <span class="navbar-badge badge text-bg-warning"><?= $notificacoes ?></span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <span class="dropdown-item dropdown-header"><?= $notificacoes ?> Notificações</span>
        <div class="dropdown-divider"></div>

        <a href="<?= base_url('mensagens') ?>" class="dropdown-item">
            <i class="bi bi-envelope me-2"></i>
            <?= $qteMensagensNaoLidas ?> novas mensagens
        </a>
        <div class="dropdown-divider"></div>

        <a href="<?= base_url('tarefas')?>" class="dropdown-item">
            <i class="bi bi-check2-square me-2"></i>
            <?= $qteTarefas ?> tarefas pendentes
        </a>

        <div class="dropdown-divider"></div>
        <a href="<?= base_url('financeiro/despesas')?>" class="dropdown-item"><i class="nav-icon bi bi-cash-coin me-2"></i> 
            <?= $qteDespesasNaoPagas ?> despesas não pagas
        </a>
        
    </div>
</li>