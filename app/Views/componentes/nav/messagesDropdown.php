<?php


function tempo_decorrido($data_envio)
{
    $data_envio = new DateTime($data_envio);
    $data_atual = new DateTime();
    $diferenca = $data_envio->diff($data_atual);

    if ($diferenca->y > 0) {
        return $diferenca->y . " ano(s) atrás";
    } elseif ($diferenca->m > 0) {
        return $diferenca->m . " mês(es) atrás";
    } elseif ($diferenca->d > 0) {
        return $diferenca->d . " dia(s) atrás";
    } elseif ($diferenca->h > 0) {
        return $diferenca->h . " hora(s) atrás";
    } elseif ($diferenca->i > 0) {
        return $diferenca->i . " minuto(s) atrás";
    } else {
        return "agora mesmo";
    }
}
?>
<li class="nav-item dropdown">
    <a class="nav-link" data-bs-toggle="dropdown" href="#">
        <i class="bi bi-chat-text"></i>
        <span class="navbar-badge badge text-bg-danger"><?= $qteMensagensNaoLidas??0 ?></span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <?php if (!empty($mensagensNaoLidas)): ?>
            <?php foreach ($mensagensNaoLidas as $mensagem): ?>
                <a href="<?= base_url('mensagens/ler/' . $mensagem['id']) ?>" class="dropdown-item">
                    <!--begin::Message-->
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <img src="<?= base_url('usuarios/exibirFoto/') . $mensagem['remetente_id'] ?>" alt="User Avatar" class="img-size-50 rounded-circle me-3">
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="dropdown-item-title">
                                <?= getUsername($mensagem['remetente_id']) ?>
                                <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span>
                            </h3>
                            <p class="fs-7"><?= $mensagem['assunto'] ?></p>
                            <p class="fs-7 text-secondary"><i class="bi bi-clock-fill me-1"></i>
                                <?= tempo_decorrido($mensagem['data_envio']) ?>
                            </p>
                        </div><!--end::Message-->
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <a href="#" class="dropdown-item">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="fs-7">Nenhuma mensagem não Lida!</p>
                    </div>
                </div>
            </a>
        <?php endif; ?>
        <div class="dropdown-divider"></div>
        <a href="<?= base_url('mensagens') ?>" class="dropdown-item dropdown-footer">
            Veja todas as mensagens
        </a>
    </div>
</li>