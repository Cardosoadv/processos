<div class="modal fade" id="modal_movimento" tabindex="-1" aria-labelledby="modal-movimento" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_movimento-label">Movimento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('processos/salvarMovimento')?>" method="post">
        <input type="hidden" name="numero_processo" value="<?= $processo['numero_processo'] ?? '' ?>">
          <div class="row mb-3">
            <div class="form-group col-4">
              <label for="nome" class="form-label">Data</label>
              <input type="date" class="form-control col" id="dataHora" name="dataHora" required>
            </div>
            <div class="form-group col-8">
              <label for="nome" class="form-label">Movimento</label>
              <input type="text" class="form-control col" id="nome" name="nome" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</div>