<div id="error-page">
    <i class="icon sad"></i>
    <h1>Error 404</h1>
    <h2><?= $message ?? 'Page not found' ?></h2>
    <a href="<?= $this->getPath('/') ?>" class="button">
        Mainpage
    </a>
</div>