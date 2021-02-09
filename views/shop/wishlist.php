<div class="container">
    <section id="wishlist">
        <div class="row">
            <?php $this->renderComponent("components/profileSidebar.php"); ?>
            <div id="content">
                <div class="box">
                    <h1>Wishlist</h1>
                    <div class="items">
                        <?php if (sizeof($items) == 0) : ?>
                            <div class="empty-message">
                                <i class="icon heart"></i>
                                <span>Your Wishlist is empty</span>
                                <a href="<?= $this->getPath('/') ?>">Go Shopping</a>
                            </div>
                        <?php else : ?>
                            <table cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item) : ?>
                                        <tr data-id="<?= $item->product_id ?>">
                                            <td>
                                                <div class="product-item">
                                                    <div class="image" style="background-image: url(<?= $item->product->image ?>)"></div>
                                                    <div class="data">
                                                        <h4><?= $item->product->name ?></h4>
                                                        <div class="price"><?= number_format($item->product->cost_per_item, 2, '.', '') ?> €</div>
                                                        <a href="<?= $this->getPath('/product/' . $item->product_id . '/cart') ?>" class="cart-button">
                                                            <i class="icon shopping-cart-plus"></i>
                                                            add to Cart
                                                        </a>
                                                        <div class="actions">
                                                            <a href="<?= $this->getPath('/wishlist/remove/redirect/' . $item->product->id) ?>" class="action">Remove from Wishlist</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>

                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>