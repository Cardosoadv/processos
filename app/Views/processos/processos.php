<?php 
/*
* Busca as Etiquetas do Processo
* @param $id_processo ID do processo
* @return array
*/
function etiquetasDosProcesso($id_processo){
    $etiquetas = model('ProcessosModel');
    $etiquetas = $etiquetas->joinEtiquetasProcessos($id_processo);
    return $etiquetas;
}

//Carrega as etiquetas
$tags = model('EtiquetasModel')->orderBy('nome')->findAll();

// Obtém os parâmetros atuais
$params = $_GET;

// Função auxiliar para remover um parâmetro específico
function removeParam($params, $key) {
    unset($params[$key]);
    return $params;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title><?= $titulo ?></title>
    <?= $this->include('template/header') ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?= $this->include('template/nav') ?>
        <?= $this->include('template/sidebar') ?>

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <?= $this->include('componentes/breadcrumbs') ?>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-8">
                                    <form action="" method="get" class="mb-3">
                                        <div class="input-group">
                                            <input 
                                                type="text" 
                                                name="s" 
                                                class="form-control" 
                                                placeholder="Pesquisar..." 
                                                aria-label="Pesquisar"
                                                value="<?= isset($params['s']) ? esc($params['s']) : '' ?>">
                                            <button class="btn btn-outline-secondary" type="submit">
                                                Pesquisar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-4">
                                    <select class="form-control" id="tagSelect" name="selected_tag_id">
                                        <option value="">Selecione uma Tag</option> <?php foreach ($tags as $tag): ?>
                                            <option value="<?= $tag['id_etiqueta'] ?>" <?= (isset($params['etiqueta']) && $params['etiqueta'] == $tag['id_etiqueta']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($tag['nome'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="container">
                                <div class="d-flex justify-content-end mb-3">

                                <?php if(isset($params['encerrado'])): ?>
                                <a href="<?= base_url('processos?' . http_build_query(removeParam($params, 'encerrado'))) ?>" 
                                    class="btn btn-secondary mb-2 mx-1">
                                    Todos os Processos
                                </a>
                                <?php if($params['encerrado']==1): ?>
                                    <a href="<?= base_url('processos?' . http_build_query(array_merge($params, ['encerrado' => '0']))) ?>" 
                                        class="btn btn-primary mb-2 mx-1">
                                        Em Andamento
                                    </a>
                                    <?php elseif($params['encerrado']==0): ?>
                                    <a href="<?= base_url('processos?' . http_build_query(array_merge($params, ['encerrado' => '1']))) ?>" 
                                        class="btn btn-danger mb-2 mx-1">
                                        Encerrados
                                    </a>        
                                <?php endif; ?>
                                <?php else: ?>
                                    <a href="<?= base_url('processos?' . http_build_query(array_merge($params, ['encerrado' => '0']))) ?>" 
                                        class="btn btn-primary mb-2 mx-1">
                                        Em Andamento
                                    </a>
                                <?php endif; ?>        

                                    <a href="<?= base_url('processos/novo/') ?>" 
                                        class="btn btn-success mb-2 mx-1">
                                        Novo Processo
                                    </a>
                                </div>

                                <?php if (null !== (session()->get('msg'))
                                            || (session()->get('success')) 
                                            || (session()->get('errors'))): ?>
                                    <div class="callout callout-info">
                                        <?= session()->get('msg') ?>
                                        <?= session()->get('success') ?>
                                        <?= session()->get('errors') ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-3">
                                    <?php if (empty($processos)): ?>
                                        <div class="alert alert-info">
                                            Nenhum processo encontrado.
                                        </div>
                                    <?php else: ?>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="col-3">
                                                        <a href="<?= base_url('processos?' . http_build_query(array_merge($params, ['sort' => 'numero_processo', 'order' => ($sortField === 'numero_processo' ? $nextOrder : 'asc')]))) ?>" 
                                                            class="text-decoration-none text-dark">
                                                            Número do Processo
                                                            <?php if($sortField === 'numero_processo'): ?>
                                                                <i class="fas fa-sort-<?= $sortOrder === 'asc' ? 'up' : 'down' ?>"></i>
                                                            <?php endif; ?>
                                                        </a>        
                                                    </th>
                                                    <th class="col-4">
                                                        <a href="<?= base_url('processos?' . http_build_query(array_merge($params, ['sort' => 'titulo_processo', 'order' => ($sortField === 'titulo_processo' ? $nextOrder : 'asc')]))) ?>" 
                                                            class="text-decoration-none text-dark">
                                                            Título / Cliente
                                                            <?php if($sortField === 'titulo_processo'): ?>
                                                                <i class="fas fa-sort-<?= $sortOrder === 'asc' ? 'up' : 'down' ?>"></i>
                                                            <?php endif; ?>
                                                        </a>
                                                    </th>
                                                    <th class="col-2">
                                                        <a href="<?= base_url('processos?' . http_build_query(array_merge($params, ['sort' => 'dataRevisao', 'order' => ($sortField === 'dataRevisao' ? $nextOrder : 'desc')]))) ?>" 
                                                            class="text-decoration-none text-dark">
                                                            Data Revisão
                                                            <?php if($sortField === 'dataRevisao'): ?>
                                                                <i class="fas fa-sort-<?= $sortOrder === 'asc' ? 'up' : 'down' ?>"></i>
                                                            <?php endif; ?>
                                                        </a>        
                                                    </th>
                                                    <th class="col-2">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($processos as $processo): ?>
                                                    <tr>
                                                        <td><?= esc($processo['numeroprocessocommascara']) ?>
                                                            <br/>
                                                            <?php 
                                                                $etiquetas = etiquetasDosProcesso($processo['id_processo']);
                                                                foreach ($etiquetas as $etiqueta){ 
                                                                    echo "<span class='badge mr-1' style='background-color:#".$etiqueta['cor']."; font-size: 0.5rem;' id=".$etiqueta['id_etiqueta'].">".$etiqueta['nome']." &nbsp;";
                                                                    echo "<i class='fas fa-times'></i>";
                                                                    echo "</span>";
                                                                    echo " ";
                                                                };
                                                            ?>
                                                        </td>
                                                        <td><?= esc($processo['titulo_processo']) ?><br/>
                                                            <?= esc($processo['nome']) ?>
                                                        </td>
                                                        <td><?= date('d-m-Y',strtotime(esc($processo['dataRevisao'] ?? "01-01-2000"))) ?></td>
                                                        <td>
                                                            <a href="<?= base_url('processos/editar/' . $processo['id_processo']) ?>" 
                                                                class="btn btn-sm btn-primary"
                                                                style="padding: 0.1rem 0.25rem; font-size: 0.7rem;">
                                                                Editar
                                                            </a>
                                                            <a href="<?= base_url('processos/excluir/' . $processo['id_processo']) ?>" 
                                                                class="btn btn-sm btn-danger"
                                                                style="padding: 0.1rem 0.25rem; font-size: 0.7rem;">
                                                                Excluir
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <?= $pager->links() ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <section class="mb-4">
                                <h3>Últimos Processos Movimentados</h3>
                                <div id="processoMovimentados" class="list-group"></div>
                            </section>

                            <section>
                                <h3>Últimas Intimações</h3>
                                <div id="intimacoes" class="list-group"></div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?= $this->include('template/modals/change_user_img.php') ?>
        <?= $this->include('template/footer') ?>
    </div>

    <script>
        const BASE_URL = '<?= base_url() ?>';
        
        // Utility functions
        const formatDate = timestamp => {
            const date = new Date(timestamp);
            return date.toLocaleDateString('pt-BR');
        };

        const createProcessLink = (numeroProcesso) => {
            return `${BASE_URL}/processos/editarpornumerodeprocesso/${numeroProcesso}`;
        };

        const handleFetchError = (error, elementId) => {
            console.error('Erro:', error);
            document.getElementById(elementId).innerHTML = `
                <div class="alert alert-danger">
                    Erro ao carregar informações. Tente novamente mais tarde.
                </div>
            `;
        };

        // Process data rendering
        const renderProcessItem = (item) => `
            <div class="list-group-item">
                <a href="${createProcessLink(item.numero_processo)}" class="text-primary">
                    ${item.numero_processo}
                </a>
                <p class="mb-1">${item.nome || 'Sem descrição'}</p>
                <small class="text-muted">Data: ${formatDate(item.dataHora)}</small>
            </div>
        `;

        const renderIntimacaoItem = (item) => `
            <div class="list-group-item">
                <a href="${createProcessLink(item.numero_processo)}" class="text-primary">
                    ${item.numero_processo}
                </a>
                <p class="mb-1">${item.tipoComunicacao || 'Sem descrição'}</p>
                <small class="text-muted">Data: ${formatDate(item.data_disponibilizacao)}</small>
            </div>
        `;

        // Data fetching functions
        async function fetchProcessos() {
            try {
                const response = await fetch(`${BASE_URL}/processos/processosmovimentados/30`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                
                const container = document.getElementById('processoMovimentados');
                container.innerHTML = Array.isArray(data) && data.length > 0
                    ? data.map(renderProcessItem).join('')
                    : '<div class="list-group-item">Nenhum processo encontrado</div>';
            } catch (error) {
                handleFetchError(error, 'processoMovimentados');
            }
        }

        async function fetchIntimacoes() {
            try {
                const response = await fetch(`${BASE_URL}/intimacoes/intimacoesporperiodo/30`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                
                const container = document.getElementById('intimacoes');
                container.innerHTML = Array.isArray(data) && data.length > 0
                    ? data.map(renderIntimacaoItem).join('')
                    : '<div class="list-group-item">Nenhuma intimação encontrada</div>';
            } catch (error) {
                handleFetchError(error, 'intimacoes');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            fetchProcessos();
            fetchIntimacoes();
        });

        // Filtrar pela etiqueta
        const tagSelect = document.getElementById('tagSelect');

        tagSelect.addEventListener('change', function() {
            const selectedTagId = this.value;

            if (selectedTagId) {
                // Obtém a URL base da página atual
                const baseUrl = window.location.href.split('?')[0];
                // Cria a nova URL com o parâmetro GET
                const newUrl = `${baseUrl}?etiqueta=${selectedTagId}`;
                // Redireciona para a nova URL
                window.location.href = newUrl;
            } else {
                // Se nenhuma tag for selecionada, você pode redirecionar para uma URL sem o parâmetro, ou fazer outra coisa
                window.location.href = baseUrl; // Redireciona para a página sem filtro
            }
        });

    </script>
</body>
</html>