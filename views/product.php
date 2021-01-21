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
            <div class="actions">
                <!-- in den Warenkorb -->
            </div>
        </div>
    </article>
    <section class="more-products">
        <h2>You might need these</h2>
    <?php foreach($products as $_product) : 
    if($shownProduct->__get('id') != $_product->__get('id'))
        $this->renderComponent("components/productCard.php", ["product"=>$_product]);
    endforeach;
    ?>
    </section>

</main>