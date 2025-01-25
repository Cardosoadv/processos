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
        <form action="<?= base_url('processoobjeto/salvar')?>" method="post">
        <input type="hidden" name="numero_processo" value="<?= $processo['numero_processo'] ?? '' ?>">
        <input type="hidden" name="processo_id" value="<?= $processo['id_processo'] ?? '' ?>">
          <div class="row mb-2">
            <div class="form-group col-6">
              <label for="bairro" class="form-label">Bairro</label>
              <input type="text" class="form-control col" id="bairro" name="bairro">
            </div>
            <div class="form-group col-6">
              <label for="cidade" class="form-label">Cidade</label>
              <input type="text" class="form-control col" id="cidade" name="cidade">
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
          </div>

          <div class="row mb-2">
            <div class="form-group">
              <label for="comentario" class="form-label ">Comentário</label>
              <textarea class="form-control col w-100" id="comentario" name="comentario"></textarea>
            </div>
          </div>

          <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</div>