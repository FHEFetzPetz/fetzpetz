<div class="box">
    <div class="title-row">
        <div class="title">
            <h1>Products</h1>
            <h2><?= sizeof($products) ?> product(s) in total</h2>
        </div>
        <a href="<?= $this->getPath('/admin/products/new') ?>" class="button">
            Create Product
        </a>
    </div>
    <table cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Active</th>
                <th>Categories</th>
                <th>Created by</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($products as $product): ?>
            <tr>
                <td><span class="mobile-field">ID:</span><a href="<?= $this->getPath('/admin/products/edit/' . $product->id) ?>"><?= $product->id ?></a></td>
                <td><span class="mobile-field">Name:</span><?= $product->name ?></td>
                <td><span class="mobile-field">Price:</span><?= number_format($product->cost_per_item,2,',','.') ?> €</td>
                <td><span class="mobile-field">Active:</span><?= $product->active ? 'Yes' : 'No' ?></td>
                <td><span class="mobile-field">Categories:</span>
                    <?php if(sizeof($product->categories) == 0): ?>
                        -
                    <?php elseif(sizeof($product->categories) > 1): ?>
                        <div class="item-list">
                        <?php foreach($product->categories as $categoryId): ?>
                            <a href="<?= $this->getPath('/admin/categories/edit/' . $categoryId) ?>"><?= isset($categoryIds[$categoryId]) ? $categoryIds[$categoryId]->name : $categoryId ?></a>
                        <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <a href="<?= $this->getPath('/admin/categories/edit/' . $product->categories[0]) ?>"><?= isset($categoryIds[$product->categories[0]]) ? $categoryIds[$product->categories[0]]->name : $product->categories[0] ?></a>
                    <?php endif; ?>
                </td>
                <td><span class="mobile-field">Created by:</span><?php if(is_null($product->created_by)): ?>-<?php else: ?><a href="<?= $this->getPath('/admin/users/edit/' . $product->created_by) ?>"><?=$product->created_by ?></a><?php endif; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>