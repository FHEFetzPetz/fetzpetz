<div class="container">
    <section id="checkout">
        <div class="box summary">
            <h1 class="padding">Checkout</h1>
            <div class="row">
                <div class="column">
                    <h3>Cart</h3>
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
                                                <div class="image" style="background-image: url(<?= $item['product']->image ?>)"></div>
                                                <div class="data">
                                                    <h4><?= $item['product']->name ?></h4>
                                                    <div class="price"><?= number_format($item['product']->cost_per_item,2,'.','') ?> €</div>
                                                    <div class="actions">
                                                        <a href="<?= $this->getPath('/cart/remove/' . $item['product']->id, ['source' => 'checkout']) ?>" class="action">Remove from Cart</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="item-price"><?= number_format($item["total"], 2,'.','') ?> €</div>
                                        </td>
                                        <td class="quantity">
                                            <span><?= $item['quantity'] ?></span>
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
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <h3>Shipping Address <small>(<a href="<?= $this->getPath('/checkout/address',['type'=>'update']) ?>">change</a>)</small></h3>
                    <p><?= $address['firstName'] ?> <?= $address['lastName'] ?></p>
                    <p><?= $address['street'] ?></p>
                    <p><?= $address['zip'] ?> <?= $address['city'] ?><?= strlen($address['state'] > 0) ? (' (' . $address['state'] . ')') : '' ?></p>
                    <p><?= $address['country'] ?></p>
                    <p><?= $address['phoneNumber'] ?></p>
                </div>
                <?php if($address['billingAddress'] != null): ?>
                <div class="column">
                    <h3>Billing Address <small>(<a href="<?= $this->getPath('/checkout/address',['type'=>'update']) ?>">change</a>)</small></h3>
                    <p><?= $address['billingAddress']['firstName'] ?> <?= $address['billingAddress']['lastName'] ?></p>
                    <p><?= $address['billingAddress']['street'] ?></p>
                    <p><?= $address['billingAddress']['zip'] ?> <?= $address['billingAddress']['city'] ?><?= strlen($address['billingAddress']['state'] > 0) ? (' (' . $address['billingAddress']['state'] . ')') : '' ?></p>
                    <p><?= $address['billingAddress']['country'] ?></p>
                    <p><?= $address['billingAddress']['phoneNumber'] ?></p>
                </div>
                <?php endif; ?>
                <div class="column">
                    <h3>Payment Method <small>(<a href="<?= $this->getPath('/checkout/payment-method') ?>">change</a>)</small></h3>
                    <p>
                    <?php
                    switch($paymentMethod) {
                        case 'paypal': echo 'PayPal'; break;
                        case 'creditcard': echo 'Credit Card (Mastercard, VISA)'; break;
                        case 'sepa': echo 'SEPA direct debit'; break;
                        case 'sofort': echo 'SOFORT'; break;
                    }
                    ?>
                    </p>
                </div>
            </div>
            <form method="post" class="complete-order">
                <div class="info">By clicking on the 'place order' button you agree to our terms of service and privacy policy. Your items will be shipped to your given address after the payment is completed.</div>
                <button name="submit">Place Order ♥</button>
            </form>
        </div>
    </section>

    <script>
        document.querySelectorAll("#checkout table .quantity-actions .action").forEach(function(item) {
            item.addEventListener("click", function() {
                const itemRow = this.closest("tr");
                const id = itemRow.getAttribute("data-id");
                const action = this.getAttribute("data-action");
                var quantity = parseInt(this.closest(".quantity").querySelector("span").textContent);
                if (action === "add") quantity++;
                else if (quantity > 0) quantity--;

                var request = new XMLHttpRequest();
                request.open("GET", "<?= $this->getPath("/cart/quantity/") ?>" + id + "/" + quantity);
                request.addEventListener('load', function() {
                    if (request.status === 200) {
                        const data = JSON.parse(request.responseText);
                        if (data.changed_product == null) {
                            itemRow.remove();

                            if (data.item_count === 0) location.reload();
                        } else {
                            itemRow.querySelector(".quantity span").textContent = data.changed_product.quantity;
                            itemRow.querySelector(".item-price").textContent = parseFloat(data.changed_product.total).toFixed(2) + " €";
                            document.querySelector("#checkout tfoot .total-text").textContent = "Total (" + data.item_count + " Item(s)): " + parseFloat(data.total).toFixed(2) + " €";
                        }
                    } else
                        alert("nicht so nice");
                });

                request.send();
            });
        });
    </script>
</div>