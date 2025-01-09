<!--begin::Accordion-->
<div class="card card-info card-outline mb-4"><!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Intimações</div>
    </div><!--end::Header-->
    <!--begin::Body-->
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Data</th>
                    <th scope="col">Intimação</th>
                </tr>
            </thead>

            <?php if ($intimacoes ?? null) : ?>
                <?php foreach ($intimacoes as $intimacao) : ?>
                    <tbody>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($intimacao['data_disponibilizacao'])) ?></td>
                            <td><?= $intimacao['tipoComunicacao'] ?></td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div><!--end::Body-->