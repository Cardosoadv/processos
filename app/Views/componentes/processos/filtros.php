<?php
// Obtém os parâmetros atuais
$gets = $_GET;

?>
<form method="post" action="<?= base_url('processos/' . $gets); ?>">
    <div class="row">

        <div class="col-md-3">
            <div class="form-group">
                <label for="nome_interessado">Nome da Parte:</label>
                <input type="text" name="parte" id="parte" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Participação:</label>
            <div class="form-check">
                <input type="radio" class="form-check-input" name="tipo_parte" id="autor" value="autor">
                <label class="form-check-label" for="autor">Autor</label>
            </div>
            <div class="form-check">
                <input type="radio" class="form-check-input" name="tipo_parte" id="reu" value="reu">
                <label class="form-check-label" for="reu">Réu</label>
            </div>
            <div class="form-check">
                <input type="radio" class="form-check-input" name="tipo_parte" id="ambos" value="ambos">
                <label class="form-check-label" for="ambos">Ambos</label>
            </div>
        </div>
    </div>
    <input type="submit" class="btn btn-primary" value="Filtrar">
</form>
