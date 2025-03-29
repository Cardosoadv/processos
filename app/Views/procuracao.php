<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width: 210mm, height: 297mm">
    <title>Cardoso & Bruno - Sociedade de Advogados</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 297mm;
            display: flex; /* Adicionado para a faixa lateral */
        }

        .side-bar {
            width: 5mm;
            background-color: #a3843e;
            height: 100%; /* Faixa lateral ocupando toda a altura */
        }

        .container {
            width: 185mm; /* Ajuste para compensar a faixa lateral */
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 100px;
            margin-right: 20px;
        }

        .header-text h1 {
            font-size: 2em;
            margin: 0;
        }

        .header-text p {
            font-size: 1.2em;
            margin: 0;
        }

        .content {
            min-height: 200mm;
            /* Adicione estilos para o conteúdo principal aqui */
        }

        .footer {
            border-top: 1px solid #ccc;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .contact {
            display: flex;
            flex-direction: column;
        }

        .contact p {
            margin: 0;
        }

        .website {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="side-bar"></div>
    <div class="container">
        <div class="header">
            <img src="<?= base_url('public/dist/assets/img/LogoCeB.png') ?>" alt="Logo Cardoso & Bruno" class="logo">
            <div class="header-text">
                <h1>Cardoso & Bruno</h1>
                <p>SOCIEDADE DE ADVOGADOS</p>
            </div>
        </div>
        <div class="content">
            </div>
        <div class="footer">
            <div class="contact">
                <p>(31) 99224-6996</p>
                <p>(31) 99217-4834</p>
            </div>
            <p class="website">www.cardosoebruno.adv.br</p>
        </div>
    </div>
</body>
</html>