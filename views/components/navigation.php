<?php
$modelService = $this->kernel->getModelService();
$categories = $modelService->find(\App\FetzPetz\Model\Category::class, ['parent' => null]);
$desktopCategoryLimit = 3;

?>
<nav id="navigation" <?php if (isset($slim) && $slim) { ?> class="slim" <?php } ?>>
    <div class="menu-overlay">
        <div class="menu">
            <div class="header">
                <div class="close"><i class="icon times"></i></div>
                <div class="title">Menu</div>
            </div>
            <div class="categories">
                <?php
                $index = 0;
                foreach ($categories as $category) {
                    $index++;
                    $selected = isset($selectedCategory) && $selectedCategory->id == $category->id; ?>
                    <a href="<?= $this->getPath('/category/' . $category->id); ?>" class="item<?= ($index <= $desktopCategoryLimit) ? ' desktop' : '' ?><?= $selected ? ' selected' : '' ?>">
                        <span><?= $category->name ?></span>
                        <i class="icon chevron-right"></i>
                    </a>
                    <?php if ($selected) :
                        foreach ($category->getChildren($modelService) as $child) : ?>
                            <a href="<?= $this->getPath('/category/' . $child->id); ?>" class="item child">
                                <span><?= $child->name ?></span>
                            </a>
                <?php endforeach;
                    endif;
                }
                ?>
            </div>
            <div class="mobile">
                <a href="<?= $this->getPath('/wishlist'); ?>" class="item">
                    <span>Wishlist</span>
                </a>
                <a href="<?= $this->getPath('/cart'); ?>" class="item">
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
            </div>
        </div>
    </div>
    <div class="inner">
        <div class="menu-toggle">
            <i class="icon bars"></i>
        </div>
        <div class="logo-holder" <?php if (isset($slim) && $slim) : ?> style="padding-left: 0" <?php endif ?>>
            <a href="/">
                <img alt="FetzPetz Logo" src="/assets/images/logo.png" class="mobile-logo">
                <img alt="FetzPetz Logo" src="/assets/images/logo-text.png" class="desktop-logo">
            </a>
        </div>
        <?php if (!isset($slim) || !$slim) : ?>
            <div class="search-box">
                <form action="<?= $this->getPath('/search') ?>" class="search-inner">
                    <input type="text" name="query" placeholder="Search for products">
                    <div class="search-button">
                        <i class="icon search"></i>
                    </div>
                </form>
            </div>
        <?php endif; ?>
        <div class="actions">
            <div class="desktop-actions">
                <a href="<?= $this->getPath('/login'); ?>" class="action"><i class="icon user"></i></a> <!-- Konditionen für anmeldung-->
                <a href="<?= $this->getPath('/wishlist'); ?>" class="action"><i class="icon heart"></i></a>
            </div>
            <a href="<?= $this->getPath('/cart') ?>" class="action"><i class="icon shopping-cart"></i></a>
        </div>
    </div>
</nav>