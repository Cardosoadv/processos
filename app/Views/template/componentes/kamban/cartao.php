<!-- Inicio da Tarefa -->
<div class="tarefa" id="tarefa_<?php echo $item['id'] ?? '' ?>" draggable="true" ondragstart="drag(event)">
    <div class="card card-info card-outline">
        <div class="card-header">
            <h5 class="card-title"><?php echo $item['task'] ?? 'Titulo' ?></h5>
            <div class="card-tools">
                <a class="btn btn-tool btn-link"><?php echo $item['id'] ?? '' ?></a>
                <a class="btn btn-tool" onclick="tarefas.edit(<?= $item['id'] ?? '' ?>)">
                    <i class="fas fa-pen"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <p> <?php echo $item['detalhes'] ?? 'Detalhes' ?></p>
        </div>
    </div>
</div><br />
<!-- Fim da Tarefa-->