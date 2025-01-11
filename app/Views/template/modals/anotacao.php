<!-- Inicio do Modal -->
<div class="modal fade" id="modal_anotacao" name="modal_anotacao">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Anotação</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="form_anotacao" name="form_anotacao" action="<?= site_url('/processos/adicionarAnotacao')?>/<?= $processo['id_processo'] ?? '' ?>" enctype="multipart/form-data">
                    <input type="hidden" name="processo_id" class="form-control"  value="<?= $processo['id_processo'] ?? '' ?>">

                    <!-- Inicio primeira linha formulário -->
                    <div class="row">
                        <div class="form-group">
                            <label>Titulo</label>
                            <input type="text" name="titulo" class="form-control">
                        </div>
                    </div><!-- fim da primeira linha formulário -->
                    <!-- Inicio segunda linha formulário -->
                    <div class="row mt-3">
                    <div class="form-group col">
                        <label>Anotação</label>
                        <textarea name="anotacao" id="anotacao-editor" style="width: 100%;">
                        
                        </textarea>
                    </div>    
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" name="close" data-bs-dismiss="modal">Fechar</button>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="privacidade" id="inlineCheckbox1" value="1" checked>
                    <label class="form-check-label" for="inlineCheckbox1">Privado</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="privacidade" id="inlineCheckbox2" value="2" disabled>
                    <label class="form-check-label" for="inlineCheckbox2">Envolvidos</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="privacidade" id="inlineCheckbox3" value="3">
                    <label class="form-check-label" for="inlineCheckbox3">Público</label>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div><!-- Fim do Modal -->