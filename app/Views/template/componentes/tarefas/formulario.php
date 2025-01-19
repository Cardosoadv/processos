<?php
$processos = model('ProcessosModel')->findAll();
$responsaveis = model('ResposavelModel')->getUsers();
?>

<form method="post" id="form_tarefa" name="form_tarefa" action="<?= site_url('/tarefas/nova') ?>">
    <div class=" row py-1">
        <div class="form-group col-md-6">
            <label>Tarefa</label>
            <div class="input-group">
                <input type="hidden" name="id_tarefa" class="form-control" value="<?= $tarefas['id_tarefa'] ?? '' ?>">
                <input type="text" name="tarefa" class="form-control" value="<?= $tarefas['tarefa'] ?? ''?>">
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>Prazo</label>
            <div class="input-group">
                <input type="date" name="prazo" value="<?= $tarefas['prazo'] ?? '' ?>" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" spellcheck="false" data-ms-editor="true">
            </div>
        </div>
    </div>

    <div class="row py-1" >
        <div class = "form-group">
            <select name="processo_id" class="form-control">
                <option value="">Selecione um processo</option>
                <?php foreach ($processos as $processo) : ?>
                    <option value="<?= $processo['id_processo'] ?>" <?= isset($selected) && $processo['id_processo'] == $selected ? 'selected' : ''?>><?= $processo['numeroprocessocommascara'] ?> - <?= esc($processo['titulo_processo']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row py-1" >
        <div class="form-group col">
            <label>Responsavel</label>
            <div class="input-group">
                <?php if (isset($responsaveis)) : ?>
                    <select name="responsavel" class="form-control" style="width: 100%;">
                        <?php foreach ($responsaveis as $responsavel) : ?>
                            <option value="<?= $responsavel['id'] ?>" <?= $responsavel['id'] == ($tarefas['responsavel']??"") ? 'selected' : ''?>><?= $responsavel['username'] ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>    
                <!-- Responsaveis -->
            </div>
        </div>
        <div class="form-group col">
            <label>Status</label>
            <div class="input-group">
                <select name="status" class="form-control" style="width: 100%;">
                    <option value="1">Backlog</option>
                    <option value="2">A Fazer</option>
                    <option value="3">Fazendo</option>
                    <option value="4">Feito</option>
                    <option value="5">Cancelado</option>
                </select>
            </div>
        </div>
        <div class="form-group col">
            <label>Prioridade</label>
            <div class="input-group">
                <select name="prioridade" class="form-control" style="width: 100%;">
                    <option value="1">Muito Baixa</option>
                    <option value="2">Baixa</option>
                    <option value="3">Média</option>
                    <option value="4">Alta</option>
                    <option value="5">Muito Alta</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group col">
            <label>Detalhes</label>
            <div class="input-group">
                <textarea name="detalhes" placeholder="detalhes" class="form-control">
                    </textarea>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Salvar</button>
</form>