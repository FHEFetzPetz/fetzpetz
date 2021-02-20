<div class="box">
    <div class="title-row">
        <div class="title">
            <h1>Categories</h1>
            <h2><?= sizeof($categories) ?> category(ies) in total</h2>
        </div>
        <a href="<?= $this->getPath('/admin/categories/new') ?>" class="button">
            Create Category
        </a>
    </div>
    <table cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Active</th>
                <th>Parent</th>
                <th>Products</th>
                <th>Created by</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($categories as $category): ?>
            <tr>
                <td><span class="mobile-field">ID:</span><a href="<?= $this->getPath('/admin/categories/edit/' . $category->id) ?>"><?= $category->id ?></a></td>
                <td><span class="mobile-field">Name:</span><?= $category->name ?></td>
                <td><span class="mobile-field">Active:</span><?= $category->active ? 'Yes' : 'No' ?></td>
                <td><span class="mobile-field">Parent:</span><?php if(is_null($category->parent_object)): ?>-<?php else: ?><a href="<?= $this->getPath('/admin/categories/edit/' . $category->parent_object->id) ?>"><?=$category->parent_object->name ?></a><?php endif; ?></td>
                <td><span class="mobile-field">Products:</span><?= $category->product_count ?? 0 ?></td>
                <td><span class="mobile-field">Created by:</span><?php if(is_null($category->created_by)): ?>-<?php else: ?><a href="<?= $this->getPath('/admin/users/edit/' . $category->created_by) ?>"><?=$category->created_by ?></a><?php endif; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>