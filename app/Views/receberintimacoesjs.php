<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="<?php echo base_url('testes/processarIntimacoes') ?>" method="post" enctype="multipart/form-data">
        <label for="json">Selecione o arquivo JSON:</label>
        <input type="file" name="json" id="json">
        <input type="submit" value="Enviar">
    </form>
</body>

</html>