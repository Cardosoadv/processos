<h1>Lista de Decisões</h1>
<a href="<?= base_url('/decisoes/criar') ?>">Criar Nova Decisão</a>

<ul>
    <?php foreach ($decisoes as $decisao): ?>
        <li><a href="<?= base_url('/decisoes/exibir/' . $decisao['id_decisao']) ?>">Decisão ID: <?= $decisao['id_decisao'] ?> - <?= $decisao['dados']['numero_processo'] ?? 'Sem número' ?></a></li>
    <?php endforeach; ?>
</ul>

<?php if (session()->getFlashdata('mensagem')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('mensagem') ?></div>
<?php endif; ?>