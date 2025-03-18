<!--begin::Accordion-->
<div class="card card-success card-outline mb-4"><!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Vinculação</div>
        <div class="card-tools">
            <a data-bs-toggle="modal" data-bs-target="#modal_vinculacao" class="btn btn-secondary">
                <i class="fas fa-plus"></i>
                Vinculação
            </a>
        </div>
    </div><!--end::Header-->
    <!--begin::Body-->
    <div class="card-body">
        <table class="table table-striped" >
            <thead>
                <tr>
                    <th scope="col">Processo</th> 
                    <th scope="col">Tipo</th>
                    <th scope="col">Ação</th>
                </tr>
            </thead>
            <?php if ($vinculos ?? null) : ?>
                <?php foreach ($vinculos as $vinculo) : ?>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">
                                <a href="<?= base_url('processos/editar/' . $vinculo['id_processo_vinculado']) ?>" style="font-size: 0.8rem; text-decoration: none; color: inherit;">
                                    <?= $vinculo['numeroprocessocommascara'] ?>
                                </a>
                            </td>
                            <td style="font-size: 0.7rem; text-decoration: none; color: inherit; text-align: center;">
                                <a href="<?= base_url('processos/editar/' . $vinculo['id_processo_vinculado']) ?>" style="font-size: 0.8rem; text-decoration: none; color: inherit;">    
                                    <?= $vinculo['tipo_vinculo'] ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= base_url('processos/excluirvinculo/' . $vinculo['id_vinculo'] . '/'.$processo['id_processo']) ?>" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div><!--end::Body-->