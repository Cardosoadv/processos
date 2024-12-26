<!--begin::Accordion-->
<div class="card card-primary card-outline mb-4"><!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Intimações</div>
        
    </div><!--end::Header-->

    <!--begin::Body-->
    <div class="card-body">
            <?php if ($intimacoes ?? null) : ?>
                <?php foreach ($intimacoes as $intimacao) : ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="intimacao<?= $intimacao['id_intimacao'] ?>">
                                <?= $intimacao['tipoComunicacao'] ?>
                            </button></h2>
                        <div id="intimacao<?= $intimacao['id_intimacao'] ?>" class="accordion-collapse collapse" data-bs-parent="#<?= $intimacao['id_intimacao'] ?>">
                            <div class="accordion-body"><?= $intimacao['texto'] ?><br><br>
                                Criada em <?= date('d/m/Y H:i', strtotime($intimacao['data_disponibilizacao'])) ?><br>
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div><!--end::Body-->