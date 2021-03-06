<?php 
$modelService = $this->kernel->getModelService(); 
$paymentReference = $order->getPaymentReference($modelService);
$shippingAddress = $order->getShippingAddress($modelService);
$billingAddress = $order->billing_address_id != null ? $order->getBillingAddress($modelService) : null;
?>
<div class="box order">
    <div class="title-row">
        <div class="title">
            <h1>Order #<?= sprintf('%04d', $order->id) ?></h1>
            <h2>Placed on <?= $order->created_at->format('d.m.Y') ?>, Status: <?= $order->order_status ?></h2>
        </div>
        <a href="<?= $this->getPath('/admin/orders/' . $order->id . '/status') ?>" class="button">
            Update Status
        </a>
    </div>

    <div class="row">
        <div class="column">
            <h3>Items</h3>
            <div class="items">
                <table cellspacing="0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $items = $order->getProducts($modelService);
                    foreach ($items as $item) : ?>
                        <tr data-id="<?= $item->product->id ?>">
                            <td>
                                <div class="product-item">
                                    <div class="image" style="background-image: url(<?= $item->product->image ?>)"></div>
                                    <div class="data">
                                        <h4><?= $item->product->name ?></h4>
                                        <div class="price"><?= number_format($item->cost_per_item, 2, '.', '') ?> €</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="item-price"><?= number_format($item->cost_per_item * $item->amount, 2, '.', '') ?> €</div>
                            </td>
                            <td class="quantity">
                                <span><?= $item->amount ?></span>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3" class="total">
                            <span class="total-text">Total (<?= sizeof($items) ?> Item(s)): <?= number_format($order->getTotal($modelService), 2, '.', '') ?> €</span>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="column">
            <h3>Shipping Address</h3>
            <p><?= $shippingAddress->firstname ?> <?= $shippingAddress->lastname ?></p>
            <p><?= $shippingAddress->street ?></p>
            <p><?= $shippingAddress->zip ?> <?= $shippingAddress->city ?><?= strlen($shippingAddress->state > 0) ? (' (' . $shippingAddress->state . ')') : '' ?></p>
            <p><?= $shippingAddress->country ?></p>
            <p><?= $shippingAddress->phone_number ?></p>
        </div>
        <?php if ($billingAddress != null) : ?>
            <div class="column">
                <h3>Billing Address</h3>
                <p><?= $billingAddress->firstname ?> <?= $billingAddress->lastname ?></p>
                <p><?= $billingAddress->street ?></p>
                <p><?= $billingAddress->zip ?> <?= $billingAddress->city ?><?= strlen($billingAddress->state > 0) ? (' (' . $billingAddress->state . ')') : '' ?></p>
                <p><?= $billingAddress->country ?></p>
                <p><?= $billingAddress->phone_number ?></p>
            </div>
        <?php endif; ?>
        <div class="column">
            <h3>Payment Method</h3>
            <p>
                <?php
                switch ($paymentReference->payment_method) {
                    case 'paypal':
                        echo 'PayPal';
                        break;
                    case 'creditcard':
                        echo 'Credit Card (Mastercard, VISA)';
                        break;
                    case 'sepa':
                        echo 'SEPA direct debit';
                        break;
                    case 'sofort':
                        echo 'SOFORT';
                        break;
                }
                ?>
            </p>
        </div>
    </div>
</div>