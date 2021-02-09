<main id="product">
    <article class="product-view">
        <div class="image"><img src="<?= $shownProduct->image ?>" alt="product"></div>
        <div class="data">
            <h1 class="title">
                <?= $shownProduct->name ?>
            </h1>
            <div class="price">
                <span><?= $shownProduct->cost_per_item ?> €</span>
            </div>
            <div class="description">
                <span><?= nl2br($shownProduct->description) ?></span>
            </div>
            <a href="<?= $this->getPath('/product/' . $shownProduct->id . '/cart') ?>" class="cart-button">
                <i class="icon shopping-cart-plus"></i>
                add to Cart
            </a>
            <div class="actions">
                <a href="mailto:?to=&body=<?= $this->getAbsolutePath('/product/'.$shownProduct->id) ?>" class="button share-button">
                    <i class="icon share"></i>
                </a>
                <div class="button like-button<?= in_array($shownProduct->id, $wishlist) ? ' active' : '' ?>">
                    <i class="icon heart"></i>
                </div>
            </div>
        </div>
    </article>
    <section class="more-products">
        <h2>You might need these</h2>
        <?php foreach ($products as $_product) :
            if ($shownProduct->id != $_product->id)
                $this->renderComponent("components/productCard.php", ["product" => $_product]);
        endforeach;
        ?>
    </section>
</main>

<script>
    document.querySelector('.product-view .like-button').addEventListener('click', function() {
        toggleLikeProduct('<?= $shownProduct->id ?>', this)
    })
</script>