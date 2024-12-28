<!-- Inicio da Tarefa -->
<div class="tarefa" id="<?php echo $a_fazer['id'] ?? ''?>" draggable="true" ondragstart="drag(event)">
    <div class="card card-info card-outline">
        <div class="card-header">
            <h5 class="card-title"><?php echo $a_fazer['task']?? 'Titulo'?></h5>
            <div class="card-tools">
                <a class="btn btn-tool btn-link"><?php echo $a_fazer['id'] ?? '' ?></a>
                <a class="btn btn-tool" onclick="tarefas.edit(<?= $a_fazer['id']?? '' ?>)">
                    <i class="fas fa-pen" onclick="tarefas.edit(<?= $a_fazer['id']?? '' ?>)"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
          <p>  <?php echo $a_fazer['detalhes'] ?? 'Detalhes' ?></p>
        </div>
    </div>
</div><br/>
<!-- Fim da Tarefa-->