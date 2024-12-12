<!-- Inicio do Modal -->
<div class="modal fade" id="modal_contas" name="modal_contas">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastrar Contas</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="window._sistema.contas.close()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <form method="post" id="form_contas" name="form_contas" action="<?= site_url('/accounts/adicionar') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="id" class="form-control" value="">

                    <!-- Inicio primeira linha formulário --><div class="row">
                        <div class="form-group">
                            <label>Conta</label>
                            <input type="text" name="account" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Comentários</label>
                            <input type="text" name="comments" class="form-control">
                        </div>

                    </div><!-- fim da primeira linha formulário -->
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="window._sistema.contas.close()" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div><!-- Fim do Modal -->