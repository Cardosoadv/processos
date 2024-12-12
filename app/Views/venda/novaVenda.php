<!DOCTYPE html>
<html lang="pt-BR"><!--begin::Head-->

<head>
  <title>Conselhos | Nova Venda</title><!--begin::Primary Meta Tags-->
  <?= $this->include('template/header') ?>
</head><!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"><!--begin::App Wrapper-->
  <div class="app-wrapper">
    <?= $this->include('template/nav') ?>
    <?= $this->include('template/sidebar') ?>

    <!--begin::App Main-->
    <main class="app-main"><!--begin::App Content Header-->
      <div class="app-content-header"><!--begin::Container-->
        <div class="container-fluid"><!--begin::Row-->
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Nova Venda</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Nova Venda
                </li>
              </ol>
            </div>
          </div><!--end::Row-->
        </div><!--end::Container-->
      </div><!--end::App Content Header--><!--begin::App Content-->
      <div class="app-content"><!--begin::Container-->
        <div class="container-fluid"><!--begin::Row-->

          <!-- Início -->
          <div class="container mt-4">

            <?php
            if (isset($_SESSION['msg'])) {
              echo '<div class="callout callout-info">';
              echo $_SESSION['msg'];
              echo '</div>';
            }
            ?>

            <div class="mt-3"><!-- inicio formulário -->

              <form method="post" id="form_venda" name="form_venda" action="<?= site_url('/vendas/adicionar') ?>" enctype="multipart/form-data">
                <input type="hidden" name="id_venda" class="form-control" value="">


                <div class="form-group">
                  <label>Venda</label>
                  <input type="text" name="venda" class="form-control">
                </div>
                <div class="row mt-3">
                  <div class="form-group col-3">
                    <label>Data de Vencimento</label>
                    <input type="date" name="vencimento_dt" class="form-control">
                  </div>

                  <div class="form-group col-3">
                    <label>Valor</label>
                    <input type="number" step="0.01" name="valor" class="form-control">
                  </div>

                  <div class="form-group col-3">
                    <label>Categoria</label>
                    <?= $categoria ?>
                  </div>

                  <div class="form-group col-3">
                    <label>Cliente</label>
                    <?= $cliente ?>
                  </div>

                </div>
                <div class="row mt-3">

                  <div class="form-group">
                    <label>Comentários</label>
                    <textarea class="form-control" name="comentario" aria-label="Comentários"></textarea>
                  </div>
                </div>
                
                  <div class="form-group mt-3">
                    <label>Rateio</label>
                    <div class="row mt-3">
                      <div class="form-group col-6">
                        <label>Advogado</label>
                        <?= $advogado ?><div id="advogados"></div>
                      </div>
                      <div class="form-group col-6">
                        <label>Rateio</label>
                        <input type="text" name="rateio[0]" class="form-control">
                        <div id="rateio"></div>
                      </div>
                    </div>
                  

                  <a class="btn btn-success mt-2" onclick="adicionarLinha()">Adicionar Rateio</a>

                </div>
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-primary">Salvar</button>
              </form>
            </div><!-- fim formulário -->

          </div>
          <!-- Fim -->

        </div><!--end::Container-->
      </div><!--end::App Content-->
    </main><!--end::App Main-->

    <?= $this->include('template/modals/change_user_img.php') ?>
    <?= $this->include('template/footer') ?>
</body><!--end::Body-->

<script>
  var i = 0;

function adicionarLinha() {
  i++;
  /**
   * Inclui um novo input no HTML para lançamento de novo Advogado
   */
  const local = document.getElementById("advogados");
  const advogadoSelect = document.querySelector('[name="advogado[0]"]');
    const newAdvogadoSelect = advogadoSelect.outerHTML;
    const newSelect = document.createElement('select');
    newSelect.innerHTML = newAdvogadoSelect;
    newSelect.classList.add('form-control');
    newSelect.classList.add('mt-1');
    newSelect.setAttribute('name', 'advogado['+i+']');
    local.appendChild(newSelect);

   /**
   * Inclui um novo input no HTML para lançamento do rateio do novo Advogado
   */
  const localRateio = document.getElementById("rateio");
    const newRateioInput = '<input type="text" name="rateio['+i+']" class="form-control mt-1">';
    localRateio.innerHTML += newRateioInput;








    /**
    const divElement = document.createElement("div");
    divElement.classList.add('form-group'); // Adicionar classe 'form-group'
    divElement.classList.add('col-8'); // Adicionar classe 'col-3'
    divElement.classList.add('mt-3'); // Adicionar classe 'mt-3'

    // Criar o primeiro input (texto)
    const inputAdvogado = document.createElement("input");
    inputAdvogado.type = "number";
    inputAdvogado.name = "advogado"; // Definir o nome do input
    inputAdvogado.classList.add('form-control');
    inputAdvogado.classList.add('col-3');

    // Criar o segundo input (número)
    const inputRateio = document.createElement("input");
    inputRateio.type = "number";
    inputRateio.name = "rateio"; // Definir o nome do input
    inputRateio.classList.add('form-control');
    inputRateio.classList.add('col-3');

    // Adicionar os inputs à nova linha
    divElement.appendChild(inputAdvogado);
    divElement.appendChild(inputRateio);
    local.appendChild(divElement);
 */
  }





</script>


</html>