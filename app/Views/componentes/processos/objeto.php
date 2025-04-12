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
                    <th scope="col"></th>
                </tr>
            </thead>
            <?php if ($objetos ?? null) : ?>
                <?php foreach ($objetos as $objeto) : ?>
                    
                        <tbody>
                            <tr>
                                <td style="padding: 0.1rem 0.25rem; font-size: 0.9rem;"><?= esc($objeto['bairro']) ?></td>
                                <td style="padding: 0.1rem 0.25rem; font-size: 0.9rem;"><?= esc($objeto['quadra']) ?></td>
                                <td style="padding: 0.1rem 0.25rem; font-size: 0.9rem;"><?= esc($objeto['lote']) ?></td>
                                <td style="padding: 0.1rem 0.25rem; font-size: 0.9rem;">
                                    <a href="<?= base_url('objetos/editar/' . $objeto['id_objeto']) ?>" >
                                        <i class="bi bi-pencil-square" style="cursor: pointer;"> </i>
                                    </a>
                                    <a href="<?= base_url('processos/desvincularObjeto/'.$processo['id_processo'] .'/'. $objeto['id_objeto']) ?>" >
                                        <i class="bi bi-trash" style="cursor: pointer;"> </i>
                                    </a>
                                </td>

                            </tr>
                        </tbody>
                    
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div><!--end::Body-->