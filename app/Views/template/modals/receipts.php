<!-- Inicio do Modal -->
<div class="modal fade" id="modal_receipts" name="modal_receipts">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastrar Recebimento</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="window._sistema.vendas.close()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="form_receipts" name="form_receipts" action="<?= site_url('/revenues/receber') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="revenues_id" class="form-control" value="">

                    <!-- Inicio primeira linha formulário -->
                    <div class="row">
                        <div class="form-group">
                            <label>Receber</label>
                            <input type="text" name="receipt_revenue" class="form-control" disabled>
                        </div>

                    </div><!-- fim da primeira linha formulário -->
                    <!-- Inicio segunda linha formulário -->
                    <div class="row mt-3">

                        <div class="form-group col">
                            <label>Data de Recebimento</label>
                            <input type="date" name="receipt_dt" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label>Valor</label>
                            <input type="text" name="receipt_value" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label>Multa</label>
                            <input type="number" step="0.01" name="receipt_late_fee" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label>Juros</label>
                            <input type="number" step="0.01" name="receipt_interest" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label>Encargos</label>
                            <input type="number" step="0.01" name="receipt_charges" class="form-control">
                        </div>


                        <div class="form-group col">
                            <label>Conta</label>
                            <?= $AccountOption ?>
                        </div>

                    </div><!-- fim da segunda linha formulário -->

                    <!-- inicio teste rateio --->
                    <div class="row mt-4">
                    <center><h1> Rateio </h1></center>
                        <div class="col-6">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Advogado</label>
                                <?= $ReceiptUserOption1 ?>
                                <?= $ReceiptUserOption2 ?>
                                <?= $ReceiptUserOption3 ?>
                            </div>
                            <div class="form-group col-6">
                                <label>Rateio</label>
                                <input type="number" step="0.01" name="receipt_share_user1" class="form-control mt-1">
                                <input type="number" step="0.01" name="receipt_share_user2" class="form-control mt-1">
                                <input type="number" step="0.01" name="receipt_share_user3" class="form-control mt-1">
                            </div>

                        </div>
                        </div>
                        <div class="col-6">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Advogado</label>
                                <?= $ReceiptUserOption4 ?>
                                <?= $ReceiptUserOption5 ?>
                                <?= $ReceiptUserOption6 ?>
                            </div>
                            <div class="form-group col-6">
                                <label>Rateio</label>
                                <input type="number" step="0.01" name="receipt_share_user4" class="form-control mt-1">
                                <input type="number" step="0.01" name="receipt_share_user5" class="form-control mt-1">
                                <input type="number" step="0.01" name="receipt_share_user6" class="form-control mt-1">
                            </div>

                        </div>
                        </div>
                    </div>
                    <!-- Fim rateio -->

                    <div class="form-group row mt-2"><br>
                        <div class="form-group">
                            <label>Comentários</label>
                            <textarea name="receipt_comments" rows="3" style="width: 100%;"></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" name="close" onclick="window._sistema.vendas.close()" data-bs-dismiss="modal">Fechar</button>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="receipt_reconciled" name="receipt_reconciled">
                    <label class="form-check-label" for="receipt_reconciled">Conciliado?</label>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div><!-- Fim do Modal -->