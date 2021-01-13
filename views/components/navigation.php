<?php
$categories = $this->kernel->getModelService()->find(\App\FetzPetz\Model\Category::class);
$desktopCategoryLimit = 3;

?>
<div id="navigation">
    <div class="menu-overlay">
        <div class="menu">
            <div class="header">
                <div class="close"><i class="icon times"></i></div>
                <div class="title">Menu</div>
            </div>
            <div class="categories">
                <?php
                    $index = 0;
                    foreach($categories as $category) { $index++; ?>
                    <div class="item<?php if($index <= $desktopCategoryLimit) echo " desktop" ?>">
                        <span><?= $category->__get('name') ?></span>
                        <i class="icon chevron-right"></i>
                    </div>
                    <?php }
                ?>
            </div>
            <div class="mobile">
                <div class="item">
                    <span>Wishlist</span>
                </div>
                <div class="item margin-top">
                    <span>Log in</span>
                </div>
                <div class="item color">
                    <span>Sign up</span>
                </div>
            </div>
        </div>
    </div>
    <div class="inner">
        <div class="menu-toggle">
            <i class="icon bars"></i>
        </div>
        <div class="logo-holder" <?php if(isset($showSearch) && !$showSearch): ?> style="padding-left: 0"<?php endif ?>>
            <a href="/">
                <img alt="FetzPetz Logo" src="/assets/images/logo.png" class="mobile-logo">
                <img alt="FetzPetz Logo" src="/assets/images/logo-text.png" class="desktop-logo">
            </a>
        </div>
        <?php if(!isset($showSearch) || $showSearch): ?>
        <div class="search-box">
            <div class="search-inner">
                <input type="text" placeholder="Search for products">
                <div class="search-button">
                    <i class="icon search"></i>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="actions">
            <div class="desktop-actions">
                <div class="action"><i class="icon user"></i></div>
                <div class="action"><i class="icon heart"></i></div>
            </div>
            <div class="action"><i class="icon shopping-cart"></i></div>
        </div>
    </div>
</div>