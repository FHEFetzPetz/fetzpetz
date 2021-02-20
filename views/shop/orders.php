<?php $modelService = $this->kernel->getModelService(); ?>
<div class="container">
    <section id="shop">
        <div class="row">
            <?php $this->renderComponent("components/profileSidebar.php"); ?>
            <div id="content">
                <div class="box">
                    <h1>My Orders</h1>
                    <div class="items">
                        <?php if (sizeof($orders) == 0) : ?>
                            <div class="empty-message">
                                <i class="icon shopping-cart"></i>
                                <span>No orders placed yet</span>
                                <a href="<?= $this->getPath('/') ?>">Go Shopping</a>
                            </div>
                        <?php else : ?>
                            <table cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Recipient</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order) :
                                        $address = $order->getShippingAddress($modelService) ?>
                                        <tr>
                                            <td>
                                                <div class="product-item">
                                                    <div class="image" style="background-image: url(<?= $order->getProducts($modelService)[0]->product->image ?>)"></div>
                                                    <div class="data">
                                                        <div class="mobile-row">
                                                            <span class="mobile-field">Order-Number</span>
                                                            <h4>Order #<?= sprintf('%04d',$order->id) ?></h4>
                                                        </div>
                                                        <div class="price">
                                                            <div class="mobile-row">
                                                                <span class="mobile-field">Total</span>
                                                                <?= number_format($order->getTotal($modelService),2,'.','') ?> €
                                                            </div>
                                                        <div class="actions">
                                                            <div class="mobile-row">
                                                                <span class="mobile-field">Actions</span>
                                                                <a href="<?= $this->getPath('/profile/orders/' . $order->id) ?>" class="action">View Order</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="mobile-field">Recipient</span>
                                                <div class="item-price"><?= $address->firstname . ' ' . $address->lastname ?></div>
                                            </td>
                                            <td class="quantity">
                                                <span class="mobile-field">Status</span>
                                                <span><?= $order->order_status ?></span>
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