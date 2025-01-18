<!DOCTYPE html>
<html>
<head>
    <title>Upload de Arquivo</title>
</head>
<body>
    <h1>Upload de Arquivo</h1>

    <?php if (isset($errors)): ?>
        <div style="color: red;">
            <?= $errors ?>
        </div>
    <?php endif; ?>

    <?= form_open_multipart('importacao/process') ?>
        <input type="file" name="userfile">
        <br><br>
        <button type="submit">Enviar Arquivo</button>
    <?= form_close() ?>

</body>
</html>