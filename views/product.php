<main id="product">

    <article class="product-view">
        <div class="image"><img src="<?= $shownProduct->__get("image") ?>" alt="product"></div>
        <div class="data">
            <h1 class="title">
                <?= $shownProduct->__get("name") ?>
            </h1>
            <div class="price">
                <span><?= $shownProduct->__get("cost_per_item") ?> €</span>
            </div>
            <div class="description">
                <span><?= nl2br($shownProduct->__get("description")) ?></span>
            </div>
            <a href="<?= $this->getPath('/product/' . $shownProduct->__get('id') . '/cart') ?>" class="cart-button">
                <i class="icon shopping-cart-plus"></i>
                add to Cart
            </a>
            <div class="actions">
                <div class="button share-button">
                    <i class="icon share"></i>
                </div>
                <div class="button like-button">
                    <i class="icon heart"></i>
                </div>
            </div>
        </div>
    </article>
    <section class="more-products">
        <h2>You might need these</h2>
        <?php foreach ($products as $_product) :
            if ($shownProduct->__get('id') != $_product->__get('id'))
                $this->renderComponent("components/productCard.php", ["product" => $_product]);
        endforeach;
        ?>
    </section>

</main>