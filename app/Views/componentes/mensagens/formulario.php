<?php
$users  = model('ResposavelModel')->orderBy('username')->findAll();
?>

<form method="post" id="form_mensagens" name="form_mensaens" action="<?= site_url('mensagens/salvar') ?>">
    <input type="hidden" name="id" value="<?= $mensagem['id'] ?? '' ?>">

    <div class="row mb-3">

        <div class="form-group col">
            <label for="destinatario_id">Destinatário</label>
            <select name="destinatario_id" class="form-control">
                <option value="">Selecione um Destinatário</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= isset($destinatario_id) && $destinatario_id == $user['id'] ? 'selected' : '' ?>>
                        <?= $user['username'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    
    <div class="row mb-3">
        <div class="form-group col">
            <label for="assunto">Assunto</label>
            <input type="text" class="form-control" name="assunto" id="assunto" value="<?= $mensagens['assunto'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="conteudo">Conteúdo</label>
            <textarea class="form-control" name="conteudo" id="conteudo"><?= $mensagens['conteudo'] ?? '' ?></textarea>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('mensagens/') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>