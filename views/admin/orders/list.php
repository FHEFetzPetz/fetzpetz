<div class="box">
    <div class="title-row">
        <div class="title">
            <h1>Orders</h1>
            <h2><?= sizeof($orders) ?> order(s) in total</h2>
        </div>
    </div>
    <table cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>User Id</th>
                <th>Recipient</th>
                <th>Created at</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($orders as $order): ?>
            <tr>
                <td><span class="mobile-field">ID:</span><a href="<?= $this->getPath('/admin/orders/' . $order->id) ?>"><?= $order->id ?></a></td>
                <td><span class="mobile-field">Items:</span><?= sizeof($order->order_items) ?></td>
                <td><span class="mobile-field">Total:</span><?= number_format($order->total,2,',','.') ?> €</td>
                <td><span class="mobile-field">Status:</span><?= $order->order_status ?></td>
                <td><span class="mobile-field">User Id:</span><a href="<?= $this->getPath('/admin/users/edit/' . $order->user_id) ?>"><?= $order->user_id ?></a></td>
                <td><span class="mobile-field">Recipient:</span><?= $order->shipping_address->firstname . ' ' . $order->shipping_address->lastname ?></td>
                <td><span class="mobile-field">Created at:</span><?= $order->created_at->format('H:i d.m.Y') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>