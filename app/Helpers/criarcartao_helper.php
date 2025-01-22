<?php



function criarcartao(array $items){

    $processoModel = model('ProcessosModel');
    $data = [];
    foreach($items as $item){

        $processo = $processoModel->where('id_processo', $item['processo_id'])->first();
        $numeroProcesso = $processo['numeroprocessocommascara'] ?? "";
        $idProcesso = $processo['id_processo'] ?? "";

        $html1 = "<div class='tarefa draggable' id='tarefa_".$item['id_tarefa']."' draggable='true' data-id='".$item['id_tarefa']."'>";
        
        if($item['responsavel'] == user_id()){
            $html2 = "<div class='card card-info card-outline mb-1'>";
        }else{
            $html2 = "<div class='card card-warning card-outline mb-1'>";
        }

        $html3 = "      <div class='card-header'>
                            <h5 class='card-title'>".$item['tarefa']."</h5>
                            <div class='card-tools'>
                                <a class='btn btn-tool btn-link'>".$item['id_tarefa']."</a>
                                <a class='btn btn-tool edit-tarefa' 
                                data-tarefa-id='". $item['id_tarefa']."'
                                data-tarefa-nome='".$item['tarefa']."'
                                data-tarefa-detalhes='". $item['detalhes'] ."'
                                data-tarefa-prazo='". $item['prazo'] ."'
                                data-tarefa-prioridade='". $item['prioridade'] ."'
                                data-tarefa-responsavel='". $item['responsavel'] ."'
                                >
                                    <i class='fas fa-pen'></i>
                                </a>
                            </div>
                        </div>
                        <div class='card-body'>
                            <p> ".$item['detalhes']."</p>
                        </div>
                        <div class='card-footer'>
                            <a href='".site_url('processos/consultarProcesso')."/". $idProcesso."'> ".$numeroProcesso."</a>
                        </div>
                    </div>
                </div>";
        $status = $item['status'];

        $html = $html1 . $html2 . $html3;
        array_push($data,[ 'html' => $html, 'status' => $status]);
    }
    return $data; 
    
    
}
