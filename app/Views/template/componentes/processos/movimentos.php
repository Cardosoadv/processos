<!--begin::Accordion-->
<div class="card card-success card-outline mb-4"><!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Movimentação</div>
        <div class="card-tools">
            <a data-bs-toggle="modal" data-bs-target="#modal_movimento" class="btn btn-secondary">
                <i class="fas fa-plus"></i>
                Movimentação
            </a>
        </div>
    </div><!--end::Header-->
    <!--begin::Body-->
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Data</th>
                    <th scope="col">Movimento</th>
                </tr>
            </thead>
            <?php if ($movimentacoes ?? null) : ?>
                <?php foreach ($movimentacoes as $movimentos) : ?>
                    <tbody>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($movimentos['dataHora'])) ?></td>
                            <td><?= $movimentos['nome'] ?></td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div><!--end::Body-->