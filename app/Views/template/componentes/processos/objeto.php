<!--begin::Accordion-->
<div class="card card-success card-outline mb-4"><!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Objeto</div>
        <div class="card-tools">
            <a data-bs-toggle="modal" data-bs-target="#modal_objeto" class="btn btn-secondary">
                <i class="fas fa-plus"></i>
                Imóvel
            </a>
        </div>
    </div><!--end::Header-->
    <!--begin::Body-->
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Bairro</th>
                    <th scope="col">Quadra</th>
                    <th scope="col">Lote</th>
                </tr>
            </thead>
            <?php if ($objetos ?? null) : ?>
                <?php foreach ($objetos as $objeto) : ?>
                    
                        <tbody>
                            <tr>
                            <td><?= esc($objeto['bairro']) ?></td>
                                <td><?= esc($objeto['quadra']) ?></td>
                                <td><?= esc($objeto['lote']) ?></td>

                            </tr>
                        </tbody>
                    
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div><!--end::Body-->