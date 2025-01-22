<!--begin::Accordion-->
<div class="card card-secondary card-outline mb-4"><!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Anotações</div>
        <div class="card-tools">
            <a data-bs-toggle="modal" data-bs-target="#modal_anotacao" class="btn btn-secondary">
                <i class="fas fa-plus"></i> 
                Anotação</a>
        </div>
    </div><!--end::Header-->

    <!--begin::Body-->
    <div class="card-body">
        <div class="accordion" id="#Anotações">
            <?php if ($anotacoes ?? null) : ?>
                <?php foreach ($anotacoes as $anotacao) : ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Anotacao<?= $anotacao['id_anotacao'] ?>" aria-expanded="false" aria-controls="Anotacao<?= $anotacao['id_anotacao'] ?>">
                                <?= $anotacao['titulo'] ?>
                            </button></h2>
                        <div id="Anotacao<?= $anotacao['id_anotacao'] ?>" class="accordion-collapse collapse" data-bs-parent="#<?= $anotacao['titulo'] ?>">
                            <div class="accordion-body"><?= $anotacao['anotacao'] ?><br><br>
                                Criada em <?= date('d/m/Y H:i', strtotime($anotacao['created_at'])) ?><br>
                                atualizada em <?php if (($anotacao['updated_at']) != NULL) {
                                                    date('d/m/Y H:i', strtotime($anotacao['updated_at']));
                                                } else {
                                                    echo 'Sem Atualização';
                                                } ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div><!--end::Body-->