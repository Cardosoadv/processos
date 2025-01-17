<?php

function criarcartao(array $items){

    $data = [];
    foreach($items as $item){
    $html = "<div class='tarefa draggable' id='tarefa_".$item['id_tarefa']."' draggable='true' data-id='".$item['id_tarefa']."'>
                <div class='card card-info card-outline mb-1'>
                    <div class='card-header'>
                        <h5 class='card-title'>".$item['tarefa']."</h5>
                        <div class='card-tools'>
                            <a class='btn btn-tool btn-link'>".$item['id_tarefa']."</a>
                            <a class='btn btn-tool edit-tarefa' 
                            data-tarefa-id='". $item['id_tarefa']."'
                            data-tarefa-nome='".$item['tarefa']."'
                            data-tarefa-detalhes='". $item['detahes'] ."'
                            data-tarefa-prazo='". $item['prazo'] ."'
                            data-tarefa-prioridade='". $item['prioridade'] ."'
                            data-tarefa-responsavel='". $item['responsavel'] ."'
                            >
                                <i class='fas fa-pen'></i>
                            </a>
                        </div>
                    </div>
                    <div class='card-body'>
                        <p> ".$item['detahes']."</p>
                    </div>
                </div>
            </div>";
    $status = $item['status'];
    array_push($data,[ 'html' => $html, 'status' => $status]);
    }
    return $data; 
    
    
}
