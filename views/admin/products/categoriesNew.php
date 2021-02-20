<form enctype="multipart/form-data" method="post" class="box">
    <h1>New Relation</h1>
    <h2><?= $product->name ?> (ID: <?= $product->id ?>)</h2>
    <div class="input-item">
        <ul>
            <?php foreach($categories as $category): ?>
            <li>
                <input type="checkbox" id="category-<?= $category->id ?>" name="category-<?= $category->id ?>">
                <label for="category-<?= $category->id ?>"><?= $category->name ?></label>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="submit-item">
        <button name="submit">Submit</button>
    </div>
</form>