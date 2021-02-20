<div class="box">
    <div class="title-row">
        <div class="title">
            <h1>Relations</h1>
            <h2><?= $product->name ?> (ID: <?= $product->id ?>)</h2>
        </div>
        <a href="<?= $this->getPath('/admin/products/categories/' . $product->id . '/new') ?>" class="button">
            Create Relation
        </a>
    </div>
    <table cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Product-ID</th>
            <th>Name</th>
            <th>Active</th>
            <th>Created by</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($categories as $pair): $category = $pair->category; ?>
            <tr>
                <td><span class="mobile-field">ID:</span><?= $pair->id ?></td>
                <td><span class="mobile-field">Product-ID:</span><a href="<?= $this->getPath('/admin/categories/edit/' . $category->id) ?>"><?= $category->id ?></a></td>
                <td><span class="mobile-field">Name:</span><?= $category->name ?></td>
                <td><span class="mobile-field">Active:</span><?= $category->active ? 'Yes' : 'No' ?></td>
                <td><span class="mobile-field">Created by:</span><?php if(is_null($category->created_by)): ?>-<?php else: ?><a href="<?= $this->getPath('/admin/users/edit/' . $category->created_by) ?>"><?=$category->created_by ?></a><?php endif; ?></td>
                <td><span class="mobile-field">Actions:</span><a href="<?= $this->getPath('/admin/products/categories/' . $product->id . '/delete/' . $pair->id) ?>">Unlink</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>