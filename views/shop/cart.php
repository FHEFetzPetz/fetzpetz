<div class="container">
    <section id="shop">
        <div class="row">
            <?php $this->renderComponent("components/profileSidebar.php"); ?>
            <div id="content">
                <div class="box">
                    <h1>Shopping Cart</h1>
                    <div class="items">
                        <?php if (sizeof($items) == 0) : ?>
                            <div class="empty-message">
                                <i class="icon shopping-cart"></i>
                                <span>Your Cart is empty</span>
                                <a href="<?= $this->getPath('/') ?>">Go Shopping</a>
                            </div>
                        <?php else : ?>
                            <table cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item) : ?>
                                        <tr data-id="<?= $item["product"]->id ?>">
                                            <td>
                                                <div class="product-item">
                                                    <a href="<?= $this->getPath('/product/' . $item['product']->id) ?>" class="image" style="background-image: url(<?= $item['product']->image ?>)"></a>
                                                    <div class="data">
                                                        <div class="mobile-row">
                                                            <span class="mobile-field">Name:</span>
                                                            <h4><?= $item['product']->name ?></h4>
                                                        </div>
                                                        <div class="price mobile-row">
                                                            <span class="mobile-field">Unit Price:</span>
                                                            <?= number_format($item['product']->cost_per_item,2,'.','') ?> €
                                                        </div>
                                                        <div class="actions mobile-row">
                                                            <span class="mobile-field">Actions:</span>
                                                            <a href="<?= $this->getPath('/cart/remove/' . $item['product']->id) ?>" class="action">Remove from Cart</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="mobile-field">Total:</span>
                                                <div class="item-price"><?= number_format($item["total"], 2,'.','') ?> €</div>
                                            </td>
                                            <td class="quantity mobile-row">
                                                <span class="mobile-field">Quantity:</span>
                                                <span class="number"><?= $item['quantity'] ?></span>
                                                <div class="quantity-actions">
                                                    <div class="action" data-action="add"><i class="icon plus"></i></div>
                                                    <div class="action" data-action="sub"><i class="icon minus"></i></div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="total">
                                            <span class="total-text">Total (<?= sizeof($items) ?> Item(s)): <?= number_format($total, 2,'.','') ?> €</span>
                                            <br>
                                            <a href="<?= $this->getPath('/checkout/address') ?>" class="checkout-button">Proceed to Checkout</a>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="cart-data" class="hidden-data" data-value='<?= json_encode([
        'quantity' => $this->getPath("/cart/quantity/")
    ], JSON_UNESCAPED_SLASHES); ?>'></div>
    <script src="/assets/js/cart.js"></script>
</div>