<!-- Inicio da Tarefa -->
<div class="tarefa draggable" id="tarefa_<?php echo $item['id_tarefa'] ?? '' ?>" draggable="true" data-id:"Tarefa-<?php echo $item['id_tarefa'] ?? '' ?>">
    <div class="card card-info card-outline">
        <div class="card-header">
            <h5 class="card-title"><?php echo $item['tarefa'] ?? 'Titulo' ?></h5>
            <div class="card-tools">
                <a class="btn btn-tool btn-link"><?php echo $item['id_tarefa'] ?? '' ?></a>
                <a class="btn btn-tool" onclick="tarefas.edit(<?= $item['id_tarefa'] ?? '' ?>)">
                    <i class="fas fa-pen"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <p> <?php echo $item['detalhes'] ?? 'Detalhes' ?></p>
            
        </div>
    </div>
</div>
<!-- Fim da Tarefa-->