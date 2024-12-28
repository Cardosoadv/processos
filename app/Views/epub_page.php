
<div class="epub-content">
    <h1><?= $title ?></h1>
    <h2><?= $book ?></h2>
</div>
<div style="text-align: justify;">    
<?= $content ?>
</div>


<p>
<a href="<?= base_url("testes") ?>?showPage=<?= ($showPage - 1)?>">Anterior</a>
<a href="<?= base_url("testes") ?>?showPage=<?= ($showPage + 1)?>">Próxima</a>
</p>
<?= $percentage ?>