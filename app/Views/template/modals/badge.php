<!-- Modal para adicionar nova etiqueta -->
<div class="modal fade" id="addTagModal" tabindex="-1" aria-labelledby="addTagModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTagModalLabel">Adicionar Nova Etiqueta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="tagForm">
                    <div class="mb-3">
                        <label for="tagName" class="form-label">Nome da Etiqueta</label>
                        <input type="text" class="form-control" id="tagName" required>
                    </div>
                    <div class="mb-3">
                        <label for="tagColor" class="form-label">Cor</label>
                        <select class="form-select" id="tagColor">
                            <option value="bg-primary">Azul</option>
                            <option value="bg-success">Verde</option>
                            <option value="bg-warning">Amarelo</option>
                            <option value="bg-danger">Vermelho</option>
                            <option value="bg-info">Ciano</option>
                            <option value="bg-secondary">Cinza</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="addNewTag()">Salvar</button>
            </div>
        </div>
    </div>
</div>