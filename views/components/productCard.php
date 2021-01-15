<article class="product-wrap">
    <a href="#" class="product-card">
        <div class="image" style="background-image: url(<?= $product->__get("image") ?>)">
            <div class="like-button">
                <i class="icon heart"></i>
            </div>
        </div>
        <div class="data">
            <span><?= $product->__get("name") ?></span><br>
            <span class="price"><?= $product->__get("cost_per_item") ?> €</span>
        </div>
    </a>
</article>