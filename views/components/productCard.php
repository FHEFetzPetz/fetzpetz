<article class="product-wrap">
    <a href="<?= $this->getPath('/product/' . $product->id) ?>" class="product-card">
        <div class="image" style="background-image: url(<?= $product->image ?>)">
            <div class="like-button">
                <i class="icon heart"></i>
            </div>
        </div>
        <div class="data">
            <span><?= $product->name ?></span><br>
            <span class="price"><?= $product->cost_per_item ?> €</span>
        </div>
    </a>
</article>