<?php

$modelService = $this->kernel->getModelService();
$categories = $modelService->find(\App\FetzPetz\Model\Category::class, ['parent' => null, 'active' => 1],['*'],5);
$products = $modelService->find(\App\FetzPetz\Model\Product::class, ['active' => 1],['*'],5);
?>
<footer id="footer">
    <div class="row container">
        <section class="column">
            <h2>Popular Categories</h2>
            <?php foreach($categories as $category): ?>
            <a class="item" href="<?= $this->getPath('/category/' . $category->id) ?>"><?= $category->name ?></a>
            <?php endforeach; ?>
        </section>
        <section class="column">
            <h2>Popular Items</h2>
            <?php foreach($products as $product): ?>
                <a class="item" href="<?= $this->getPath('/product/' . $product->id) ?>"><?= $product->name ?></a>
            <?php endforeach; ?>
        </section>
        <section class="column">
            <h2>Company</h2>
            <a class="item" href="<?= $this->getPath('/imprint') ?>">Imprint</a>
            <a class="item" href="<?= $this->getPath('/privacy-policy') ?>">Privacy Policy</a>
            <a class="item" href="<?= $this->getPath('/terms-of-service') ?>">Terms of service</a>
            <a class="item" href="<?= $this->getPath('/faq') ?>">FAQ</a>
        </section>
    </div>
    <div class="bottom-chin container">
        <div class="logo-holder">
            <img alt="FetzPetz Logo" src="/assets/images/logo-text.png">
        </div>
        <div class="copyright-text">
            Copyright © 2021 Saskia Wohlers, Dirk Hofmann, Luca Voges<br>
            made with <i class="icon heart"></i> in Germany
        </div>
    </div>
</footer>