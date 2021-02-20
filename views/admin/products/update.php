<form enctype="multipart/form-data" method="post" class="box">
    <h1><?= $type === 'new' ? 'Create Product' : 'Edit Product' ?></h1>
    <?php if(sizeof($errors) > 0): ?>
        <ul class="errors">
            <?php foreach($errors as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <div class="input-item">
        <input id="name" name="name" type="text" placeholder="Name" maxlenght="100" value="<?= $name; ?>" required>
    </div>
    <div class="input-item">
        <textarea maxlength="2000" name="description" id="description" placeholder="Description" required><?= $description?></textarea>
    </div>
    <div class="input-item">
        <input id="costPerItem" name="costPerItem" type="text" placeholder="Cost per Item (20.00)" value="<?= $costPerItem; ?>" required>
    </div>
    <div class="input-item file">
        Image: <input id="image" name="image" accept="image/*" type="file"<?= $type == 'new' ? ' required' : '' ?>>
    </div>
    <div class="input-item checkbox">
        <input id="active" name="active" type="checkbox"<?= $active ? ' checked' : '' ?>>
        <label for="active">Product activated</label>
    </div>
    <div class="submit-item">
        <button name="submit">Submit</button>
    </div>
    <?php if($type == 'update'): ?>
    <div class="spacer">
        <a class="delete-confirmation" data-question="Confirm Product Deletion" href="<?= $this->getPath('/admin/products/delete/' . $product->id) ?>">Delete Product</a>
        <a href="<?= $this->getPath('/admin/products/categories/' . $product->id) ?>">Show Category Relations</a>
        <a href="<?= $this->getPath('/product/' . $product->id) ?>">View Product</a>
    </div>
    <?php endif; ?>
</form>