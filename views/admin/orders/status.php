<form enctype="multipart/form-data" method="post" class="box">
    <h1>Update Status</h1>
    <div class="input-item">
        <select name="status" id="status">
            <option value="cancelled"<?= $status == 'cancelled' ? ' selected' : '' ?>>Cancelled</option>
            <option value="pending"<?= $status == 'pending' ? ' selected' : '' ?>>Pending</option>
            <option value="shipped"<?= $status == 'shipped' ? ' selected' : '' ?>>Shipped</option>
            <option value="arrived"<?= $status == 'arrived' ? ' selected' : '' ?>>Arrived</option>
            <option value="return"<?= $status == 'return' ? ' selected' : '' ?>>Return</option>
            <option value="refunded"<?= $status == 'refunded' ? ' selected' : '' ?>>Refunded</option>
        </select>
    </div>
    <div class="submit-item">
        <button name="submit">Submit</button>
    </div>
</form>