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
                    <?= $this->include('template/componentes/breadcrumbs') ?>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Main Content Column -->
                        <div class="col-lg-9">
                            <div class="container">
                                

                                <div class="d-flex justify-content-end mb-3">
                                    <a href="<?= base_url('tarefas') ?>" 
                                        class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left-square"></i>
                                        Voltar
                                    </a>
                                </div>

                                <?php if (isset($_SESSION['msg'])): ?>
                                    <div class="callout callout-info">
                                        <?= session()->get('msg') ?>
                                        <?= session()->get('errors') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                                <?= $this->include('template/componentes/tarefas/formulario') ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?= $this->include('template/modals/change_user_img.php') ?>
        
        <?= $this->include('template/footer') ?>
    </div>


    <script>
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.status-select');

    selects.forEach(select => {
        select.addEventListener('change', function() {
            const tarefaId = this.dataset.tarefaId;
            const novoStatus = this.value;
            const statusId = this.dataset.statusId;
            const url = `<?=base_url("tarefas/editarstatus")?>?Tarefa-id=${tarefaId}&status-id=${novoStatus}`;
            fetch(url) // Requisição GET, sem necessidade de configurar headers ou body
            .then(response => {
                            if (response.ok) {
                                return response.json();
                            } else {
                                throw new Error('Erro na requisição AJAX'.response.message);
                            }
                        })
            .then(data => {
                            // Tratar a resposta, se necessário
                            console.log('Resposta do servidor:', data);
                            // Exibir mensagem de sucesso ou erro para o usuário
                            if (data.success) {
                                toastr.success("Tarefa movida com sucesso!");
                            } else {
                                toastr.error("Erro ao mover a tarefa.");
                            }
                        })
            .catch(error => {
                console.error('Erro:', error);
                alert("Erro ao atualizar o status");
            });
        });
    });
});
</script>

</body>
</html>