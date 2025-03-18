<div class="modal fade" id="modal-alterar-foto-perfil" tabindex="-1" aria-labelledby="modal-alterar-foto-perfil-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-alterar-foto-perfil-label">Alterar foto de perfil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('usuarios/salvar')?>" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="foto-perfil" class="form-label">Foto de perfil</label>
            <input type="hidden" name="id" value="<?= user_id() ?>">
            <input type="file" class="form-control" id="foto-perfil" name="foto-perfil" required>
          </div>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</div>