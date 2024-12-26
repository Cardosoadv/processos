<!--begin::Accordion-->
<div class="card card-primary card-outline mb-4"><!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Movimentação</div>
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
                            <td><?= $movimento['nome'] ?></td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div><!--end::Body-->