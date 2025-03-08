<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relatório de Extrato</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Configuração para página A4 */
    @page {
      size: A4;
      margin: 1.5cm;
    }
    
    @media print {
      body {
        width: 21cm;
        height: 29.7cm;
        margin: 0;
        padding: 1cm;
        font-size: 12pt;
      }
      
      .no-print {
        display: none;
      }
      
      .container-fluid {
        width: 100%;
        padding: 0;
      }
      
      /* Prevenir quebras de página indesejadas */
      table { page-break-inside: auto; }
      tr { page-break-inside: avoid; page-break-after: auto; }
      thead { display: table-header-group; }
      tfoot { display: table-footer-group; }
    }
    
    /* Estilos gerais */
    .page-header {
      border-bottom: 1px solid #ddd;
      margin-bottom: 20px;
      padding-bottom: 10px;
    }
    
    .table th {
      background-color: #f8f9fa;
    }
    
    .report-footer {
      margin-top: 30px;
      border-top: 1px solid #ddd;
      padding-top: 10px;
      font-size: 0.8em;
      text-align: center;
    }
    
    /* Valores negativos em vermelho */
    .valor-negativo {
      color: red;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <!-- Botões de controle - somente visíveis na tela -->
    <div class="row no-print mb-3">
      <div class="col-12">
        <button onclick="window.print()" class="btn btn-primary">
          <i class="bi bi-printer"></i> Imprimir Relatório
        </button>
      </div>
    </div>
    
    <!-- Cabeçalho do relatório -->
    <div class="row page-header">
      <div class="col-6">
        <h2>Extrato de Movimentações</h2>
      </div>
      <div class="col-6 text-end">
        <p>Data de emissão: <span id="current-date"></span></p>
      </div>
    </div>
    
    <!-- Conteúdo principal -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body p-0">
            <!-- Tabela de extrato -->
            <div class="table-responsive">
              <?php if (empty($extrato)): ?>
                <div class="alert alert-info">
                  Nenhum movimento encontrado.
                </div>
              <?php else: ?>
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Data</th>
                      <th>Descrição</th>
                      <th class="text-end">Valor</th>
                      <th class="text-end">Saldo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($extrato as $registro): ?>
                      <tr>
                        <td><?= date('d/m/Y', strtotime($registro['data'])) ?></td>
                        <td><?= esc($registro['descricao']) ?></td>
                        <td class="text-end <?= $registro['valor'] < 0 ? 'valor-negativo' : '' ?>">
                          R$ <?= number_format($registro['valor'],2,',','.') ?>
                        </td>
                        <td class="text-end <?= $registro['saldo'] < 0 ? 'valor-negativo' : '' ?>">
                          R$ <?= number_format($registro['saldo'],2,',','.') ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <?php 
                    $saldo_final = end($extrato)['saldo'];
                    ?>
                    <tr class="fw-bold">
                      <td colspan="3" class="text-end">Saldo Final:</td>
                      <td class="text-end <?= $saldo_final < 0 ? 'valor-negativo' : '' ?>">
                        R$ <?= number_format($saldo_final,2,',','.') ?>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Rodapé do relatório -->
    <div class="row report-footer">
      <div class="col-12">
        <p>Este documento é parte integrante da prestação de contas. Página 1 de 1</p>
      </div>
    </div>
  </div>

  <!-- Script para inserir data atual -->
  <script>
    document.getElementById('current-date').textContent = new Date().toLocaleDateString('pt-BR');
  </script>
</body>
</html>