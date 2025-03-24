<?php
$listadeProcessos = model('ProcessosModel')->orderBy('numeroprocessocommascara')->findAll();
$responsaveis = model('ResposavelModel')->getUsers();
?>

<div class="modal fade" id="modal_vinculacao" tabindex="-1" aria-labelledby="modal_vinculacao" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_movimento-label">Vinculação</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('processos/salvarVinculo')?>" method="post">
        <input type="hidden" name="id_processo_a" value="<?= $processo['id_processo'] ?? '' ?>">
          <div class="row mb-3">
            <div class="form-group">
              <label for="id_processo_b" class="form-label">Vinculo</label>
              <select name="id_processo_b" class="form-control" required>
                <option value="">Selecione um processo</option>
                <?php foreach ($listadeProcessos as $item) : ?>
                    <option value="<?= $item['id_processo'] ?>"><?= $item['numeroprocessocommascara'] ?> - <?= esc($item['titulo_processo']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group py-1">
              <label for="tipo_vinculo" class="form-label">Tipo</label>
              <input type="text" name="tipo_vinculo" class="form-control" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</div>