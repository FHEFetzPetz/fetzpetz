<?php

$modelService = $this->kernel->getModelService();
$categories = $modelService->find(\App\FetzPetz\Model\Category::class, ['parent' => null, 'active' => 1]);
$desktopCategoryLimit = 3;

$index = 0; ?>
<div class="categories">
<?php
foreach ($categories as $category):
    $index++;
    $selected = isset($selectedCategory) && ($selectedCategory->id == $category->id || ($selectedCategory->parent ?? -1) == $category->id);
    ?>
    <a href="<?= $this->getPath('/category/' . $category->id); ?>" class="item<?= ($index <= $desktopCategoryLimit) ? ' desktop' : '' ?><?= $selected ? ' selected' : '' ?>">
        <span><?= $category->name ?></span>
        <i class="icon chevron-right"></i>
    </a>
    <?php
    if ($selected) :
        foreach ($category->getChildren($modelService) as $child) :
            $childSelected = isset($selectedCategory) && ($selectedCategory->id == $child->id);
        ?>
    <a href="<?= $this->getPath('/category/' . $child->id); ?>" class="item child<?= $childSelected ? ' selected' : '' ?>">
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
    <?php if($this->isAdministrator()) : ?>
        <a href="<?= $this->getPath('/admin'); ?>" class="item">
            <span>Administration</span>
        </a>
    <?php endif; ?>
    <a href="<?= $this->getPath('/logout'); ?>" class="item margin-top">
        <span>Logout</span>
    </a>
    <a href="<?= $this->getPath('/profile'); ?>" class="item color">
        <span>My Profile</span>
    </a>
<?php else : ?>
    <a href="<?= $this->getPath('/login'); ?>" class="item margin-top">
        <span>Log in</span>
    </a>
    <a href="<?= $this->getPath('/signup'); ?>" class="item color">
        <span>Sign up</span>
    </a>
<?php endif ?>