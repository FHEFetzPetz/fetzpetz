<form method="post" class="box">
    <h1><?= $type === 'new' ? 'Create Category' : 'Edit Category' ?></h1>
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
        <select name="parent" id="parent">
            <option value="-1"<?= $parent == -1 ? ' selected' : '' ?>>None</option>
            <?php foreach($parentCategories as $parentCategory): ?>
                <option value="<?= $parentCategory->id ?>"<?= $parent == $parentCategory->id ? ' selected' : '' ?>><?= $parentCategory->name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="input-item checkbox">
        <input id="active" name="active" type="checkbox"<?= $active ? ' checked' : '' ?>>
        <label for="active">Category activated</label>
    </div>
    <div class="submit-item">
        <button name="submit">Submit</button>
    </div>
    <?php if($type == 'update'): ?>
    <div class="spacer">
        <a class="delete-confirmation" data-question="Confirm Category Deletion" href="<?= $this->getPath('/admin/categories/delete/' . $category->id) ?>">Delete Category</a>
        <a href="<?= $this->getPath('/category/' . $category->id) ?>">View Category</a>
    </div>
    <?php endif; ?>
</form>