<?php

$modelService = $this->kernel->getModelService();
$categories = $modelService->find(\App\FetzPetz\Model\Category::class, ['parent' => null]);
$desktopCategoryLimit = 3;

$index = 0; ?>
<div class="categories">
<?php
foreach ($categories as $category):
    $index++; $selected = isset($selectedCategory) && $selectedCategory->id == $category->id; ?>
    <a href="<?= $this->getPath('/category/' . $category->id); ?>" class="item<?= ($index <= $desktopCategoryLimit) ? ' desktop' : '' ?><?= $selected ? ' selected' : '' ?>">
        <span><?= $category->name ?></span>
        <i class="icon chevron-right"></i>
    </a>
    <?php
    if ($selected) :
        foreach ($category->getChildren($modelService) as $child) : ?>
    <a href="<?= $this->getPath('/category/' . $child->id); ?>" class="item child">
        <span><?= $child->name ?></span>
    </a>
<?php
        endforeach;
    endif;
endforeach;
?>
</div>
    <a href="<?= $this->getPath('/wishlist') ?>" class="item">
        <span>Wishlist</span>
    </a>
    <a href="<?= $this->getPath('/cart') ?>" class="item">
        <span>Cart</span>
    </a>
<?php if ($this->isAuthenticated()) : ?>
    <a href="<?= $this->getPath('/logout'); ?>" class="item margin-top">
        <span>Logout</span>
    </a>
    <div class="item color">
        <span>My Profile</span>
    </div>
<?php else : ?>
    <a href="<?= $this->getPath('/login'); ?>" class="item margin-top">
        <span>Log in</span>
    </a>
    <a href="<?= $this->getPath('/signup'); ?>" class="item color">
        <span>Sign up</span>
    </a>
<?php endif ?>