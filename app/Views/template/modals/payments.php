<!-- Inicio do Modal -->
<div class="modal fade" id="modal_payments" name="modal_payments">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastrar Pagamento</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="window._sistema.despesa.close()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="form_payments" name="form_payments" action="<?= site_url('/expeses/pagar') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="expense_id" class="form-control" value="">

                    <!-- Inicio primeira linha formulário -->
                    <div class="row">
                        <div class="form-group">
                            <label>Receber</label>
                            <input type="text" name="payment_expense" class="form-control" disabled>
                        </div>

                    </div><!-- fim da primeira linha formulário -->
                    <!-- Inicio segunda linha formulário -->
                    <div class="row mt-3">

                        <div class="form-group col">
                            <label>Data de Pagamento</label>
                            <input type="date" name="payment_dt" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label>Valor</label>
                            <input type="text" name="payment_value" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label>Multa</label>
                            <input type="number" step="0.01" name="payment_late_fee" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label>Juros</label>
                            <input type="number" step="0.01" name="payment_interest" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label>Encargos</label>
                            <input type="number" step="0.01" name="payment_charges" class="form-control">
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
                                <?= $PaymentUserOption1 ?>
                                <?= $PaymentUserOption2 ?>
                                <?= $PaymentUserOption3 ?>
                            </div>
                            <div class="form-group col-6">
                                <label>Rateio</label>
                                <input type="number" step="0.01" name="payment_share_user1" class="form-control mt-1">
                                <input type="number" step="0.01" name="payment_share_user2" class="form-control mt-1">
                                <input type="number" step="0.01" name="payment_share_user3" class="form-control mt-1">
                            </div>

                        </div>
                        </div>
                        <div class="col-6">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Advogado</label>
                                <?= $PaymentUserOption4 ?>
                                <?= $PaymentUserOption5 ?>
                                <?= $PaymentUserOption6 ?>
                            </div>
                            <div class="form-group col-6">
                                <label>Rateio</label>
                                <input type="number" step="0.01" name="payment_share_user4" class="form-control mt-1">
                                <input type="number" step="0.01" name="payment_share_user5" class="form-control mt-1">
                                <input type="number" step="0.01" name="paymentt_share_user6" class="form-control mt-1">
                            </div>

                        </div>
                        </div>
                    </div>
                    <!-- Fim rateio -->

                    <div class="form-group row mt-2"><br>
                        <div class="form-group">
                            <label>Comentários</label>
                            <textarea name="payment_comments" rows="3" style="width: 100%;"></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" name="close" onclick="window._sistema.despesas.close()" data-bs-dismiss="modal">Fechar</button>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="payment_reconciled" name="payment_reconciled">
                    <label class="form-check-label" for="payment_reconciled">Conciliado?</label>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div><!-- Fim do Modal -->