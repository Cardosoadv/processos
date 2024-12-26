<!DOCTYPE html>
<html lang="pt-BR"><!--begin::Head-->

<head>
  <title><?= $titulo ?></title><!--begin::Primary Meta Tags-->
  <?= $this->include('template/header') ?>
</head><!--end::Head-->


<!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <?= $this->include('template/nav') ?>
    <?= $this->include('template/sidebar') ?>

    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <?= $this->include('template/componentes/breadcrumbs') ?>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div><!--end::App Content Header-->
      
      <!--begin::App Content-->
      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-9">
               
              <!-- Início do Formulário -->
              <form action="" method="get">
                <div class="input-group mb-3">
                  <input type="text" name="s" class="form-control" placeholder="Pesquisar..." aria-label="Pequisar" aria-describedby="button-addon2">
                  <button class="btn btn-outline-secondary" type="submit" id="search">Pesquisar</button>
                </div>
              </form>
              <div class="container mt-4">
                <div class="d-flex justify-content-end">
                  <a class="btn btn-success mb-2" href="<?php echo base_url('processos/novo/'); ?>">Novo Pocesso</a>
                </div>
                <?php
                if (isset($_SESSION['msg'])) {
                  echo '<div class="callout callout-info">';
                  echo $_SESSION['msg'];
                  echo '</div>';
                }
                ?>
                <div class="mt-3">
                  <?= $table ?>
                </div>
              </div>
            </div><!-- Fim do Formulário -->
            <div class="col-3"><!-- Inicio SideBar do Formulario -->
              <h3>Ultimos Processos Movimentados</h3>
              <div id="processoMovimentados"></div>
              <h3>Ultimas Intimações</h3>
              <div id="intimacoes"></div>
            </div> <!-- Fim do SideBar do Formulario -->
          </div> <!-- Fim do Row -->
        </div><!--end::Container-->
      </div><!--end::App Content-->
    </main><!--end::App Main-->

    <?= $this->include('template/modals/change_user_img.php') ?>
    <?= $this->include('template/footer') ?>
</body><!--end::Body-->

<script>

function formatDate(timestamp) {
    const date = new Date(timestamp);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

function buscarProcesso() {
    fetch("<?=base_url('processos/processosmovimentados/120')?>") // Faz a requisição GET
      .then(response => {
        if (!response.ok) {
          throw new Error(`Erro na requisição: ${response.status}`); // Trata erros de requisição
        }
        return response.json(); // Converte a resposta para JSON
      })
      .then(data => {
        // Manipula os dados recebidos (data)
        const processoMovimentadosDiv = document.getElementById('processoMovimentados');
        processoMovimentadosDiv.innerHTML = ''; // Limpa o conteúdo atual da div

        // Exemplo de como exibir os dados. Adapte conforme a estrutura do seu JSON.
        if (data && Array.isArray(data)) { // Verifica se data existe e é um array
          data.forEach(item => {
            const paragrafo = document.createElement('p');

  // Construindo o link para o número do processo
  const linkProcesso = document.createElement('a');
  linkProcesso.href = `<?=base_url("processos/editarpornumerodeprocesso")?>/${item.numero_processo}`; // Substitua pela URL correta
  linkProcesso.textContent = item.numero_processo;

  // Construindo o parágrafo com o link
  paragrafo.innerHTML = `${linkProcesso.outerHTML}, Descrição: ${item.nome || 'Sem descrição'}, Data: ${formatDate(item.dataHora) || 'Sem descrição'}`;

  // Adicionando o parágrafo à div
  processoMovimentadosDiv.appendChild(paragrafo);
          });
        } else if (data && typeof data === 'object') { // Se for um objeto
            for (const key in data) {
                const paragrafo = document.createElement('p');
                paragrafo.textContent = `${key}: ${data[key]}`;
                processoMovimentadosDiv.appendChild(paragrafo);
            }
        } else {
            processoMovimentadosDiv.textContent = "Nenhum dado encontrado ou formato inválido.";
        }
      })
      .catch(error => {
        // Trata erros durante o processo (ex: erro de rede, erro no JSON)
        console.error('Erro:', error);
        const processoMovimentadosDiv = document.getElementById('processoMovimentados');
        processoMovimentadosDiv.textContent = 'Erro ao carregar informações.';
      });
  }

// Chama a função para buscar os dados assim que a página carrega
window.onload = buscarProcesso;


function buscarIntimacoes() {
  fetch("<?=base_url('intimacoes/intimacoesporperiodo/120')?>") // Faz a requisição GET
    .then(response => {
      if (!response.ok) {
        throw new Error(`Erro na requisição: ${response.status}`); // Trata erros de requisição
      }
      return response.json(); // Converte a resposta para JSON
    })
    .then(data => {
      // Manipula os dados recebidos (data)
      const intimacoesDiv = document.getElementById('intimacoes');
      intimacoesDiv.innerHTML = ''; // Limpa o conteúdo atual da div

      // Exemplo de como exibir os dados. Adapte conforme a estrutura do seu JSON.
      if (data && Array.isArray(data)) { // Verifica se data existe e é um array
        data.forEach(item => {
          const paragrafo = document.createElement('p');

        // Construindo o link para o número do processo
        const linkProcesso = document.createElement('a');
        linkProcesso.href = `<?=base_url("processos/editarpornumerodeprocesso")?>/${item.numero_processo}`; // Substitua pela URL correta
        linkProcesso.textContent = item.numero_processo;

        // Construindo o parágrafo com o link
        paragrafo.innerHTML = `${linkProcesso.outerHTML}, Descrição: ${item.nome || 'Sem descrição'}, Data: ${formatDate(item.dataHora) || 'Sem descrição'}`;

        // Adicionando o parágrafo à div
        intimacoesDiv.appendChild(paragrafo);

        });
        } else if (data && typeof data === 'object') { // Se for um objeto
            for (const key in data) {
                const paragrafo = document.createElement('p');
                paragrafo.textContent = `${key}: ${data[key]}`;
                processoMovimentadosDiv.appendChild(paragrafo);
            }
        } else {
            processoMovimentadosDiv.textContent = "Nenhum dado encontrado ou formato inválido.";
        }
      })
      .catch(error => {
        // Trata erros durante o processo (ex: erro de rede, erro no JSON)
        console.error('Erro:', error);
        const processoMovimentadosDiv = document.getElementById('intimacoes');
        processoMovimentadosDiv.textContent = 'Erro ao carregar informações.';
      });
  }

// Chama a função para buscar os dados assim que a página carrega
window.onload = buscarIntimacoes;
</script>


</html>