<article class="product-wrap">
    <a href="<?= $this->getPath('/product/' . $product->id) ?>" class="product-card" data-id="<?= $product->id ?>">
        <div class="image" style="background-image: url(<?= $product->image ?>)">
            <div class="like-button<?= in_array($product->id, $wishlist) ? ' active' : '' ?>">
                <i class="icon heart"></i>
            </div>
        </div>
        <div class="data">
            <span><?= $product->name ?></span><br>
            <span class="price"><?= $product->cost_per_item ?> €</span>
        </div>
    </a>
</article>