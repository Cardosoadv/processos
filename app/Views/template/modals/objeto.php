<?php

$objetosModel = model('ProcessoObjetoModel');
$listaObjetos = $objetosModel->orderby('cod_interno', 'ASC')->findAll();
$listaBairros = $objetosModel->select('bairro')->distinct()->orderby('bairro', 'ASC')->findAll();
$listaCidades = $objetosModel->select('cidade')->distinct()->orderby('cidade', 'ASC')->findAll();

?>
<div class="modal fade" id="modal_objeto" tabindex="-1" aria-labelledby="modal-objeto" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_objeto-label">Objeto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="objetoTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="vincular-tab" data-bs-toggle="tab" data-bs-target="#vincular" type="button" role="tab" aria-controls="vincular" aria-selected="true">Vincular Objeto</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="criar-tab" data-bs-toggle="tab" data-bs-target="#criar" type="button" role="tab" aria-controls="criar" aria-selected="false">Criar Objeto</button>
                    </li>
                </ul>
                <div class="tab-content" id="objetoTabsContent">
                    <div class="tab-pane fade show active" id="vincular" role="tabpanel" aria-labelledby="vincular-tab">
                        <div class="modal-card-body py-2">
                            <form action="<?= base_url('processos/vincularObjeto') ?>" method="post">
                                <input type="hidden" name="processo_id" value="<?= $processo['id_processo'] ?? '' ?>">
                                <select class="form-control" name="objeto_id">
                                    <option value="">Selecione um objeto</option>
                                    <?php foreach ($listaObjetos as $listaObjeto) : ?>
                                        <?php if (in_array($listaObjeto['id_objeto'], array_column($objetos, 'id_objeto'))) continue; ?>
                                        <option value="<?= $listaObjeto['id_objeto'] ?>"><?= $listaObjeto['cod_interno'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-secondary mt-2">Vincular Objeto</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="criar" role="tabpanel" aria-labelledby="criar-tab">
                        <div class="modal-card-body  py-2">
                            <form action="<?= base_url('processos/salvarObjeto') ?>" method="post">
                                <input type="hidden" name="processo_id" value="<?= $processo['id_processo'] ?? '' ?>">
                                <div class="row mb-2">
                                    <div class="form-group col-6">
                                        <label for="cidade" class="form-label">Cidade</label>
                                        <input type="text" class="form-control col" id="cidade" name="cidade" list="ListaCidades">

                                        <datalist id="ListaCidades">
                                            <?php foreach ($listaCidades as $cidade) : ?>
                                                <option value="<?= $cidade['cidade'] ?>"><?= $cidade['cidade'] ?></option>
                                            <?php endforeach; ?>
                                        </datalist>


                                    </div>
                                    <div class="form-group col-6">
                                        <label for="bairro" class="form-label">Bairro</label>
                                        <input type="text" class="form-control col" id="bairro" name="bairro" list="ListaBairros">

                                                <datalist id="ListaBairros">
                                                    <?php foreach ($listaBairros as $bairro) : ?>
                                                        <option value="<?= $bairro['bairro'] ?>"><?= $bairro['bairro'] ?></option>
                                                    <?php endforeach; ?>
                                                </datalist>
                                                

                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="form-group col-6">
                                        <label for="quadra" class="form-label">Quadra</label>
                                        <input type="text" class="form-control col" id="quadra" name="quadra">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="lote" class="form-label">Lote</label>
                                        <input type="text" class="form-control col" id="lote" name="lote">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="cod_interno" class="form-label">Código Interno</label>
                                        <input type="text" class="form-control col" id="cod_interno" name="cod_interno" placeholder="3 Letras Bairro + 4 Quadra + 4 Lote">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="form-group col-9">
                                        <label for="logradouro" class="form-label">Logradouro</label>
                                        <input type="text" class="form-control col" id="logradouro" name="logradouro" value="">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="numero" class="form-label">Número</label>
                                        <input type="text" class="form-control col" id="numero" name="numero" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input type="text" class="form-control col" id="complemento" name="complemento" value="">
                                    </div>
                                </div>

                                <div class="row mb-2">
        <div class="form-group col-12">
            <label for="inscricao" class="form-label">Inscrição</label>
            <input type="text" class="form-control col" id="inscricao" name="inscricao" >
        </div>
        <div class="form-group col-6">
            <label for="cartorio" class="form-label">Cartório</label>
            <input type="text" class="form-control col" id="cartorio" name="cartorio" >
        </div>
        <div class="form-group col-6">
            <label for="matricula" class="form-label">Matrícula</label>
            <input type="text" class="form-control col" id="matricula" name="matricula" >
        </div>
    </div>

                                <div class="row mb-2">
                                    <div class="form-group">
                                        <label for="comentarios" class="form-label">Comentários</label>
                                        <textarea class="form-control col" id="comentarios" name="comentarios" rows="3">
                                        </textarea>
                                    </div>
                                </div>
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>