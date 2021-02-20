<nav id="navigation" <?php if (isset($slim) && $slim) { ?> class="slim" <?php } ?>>
    <div class="menu-overlay">
        <div class="menu">
            <div class="header">
                <div class="close"><i class="icon times"></i></div>
                <div class="title">Menu</div>
            </div>
            <?php $this->renderComponent("components/sidebarContents.php"); ?>
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
                    <input type="text" name="query" placeholder="Search for products" required>
                    <button type="submit" class="search-button">
                        <i class="icon search"></i>
                    </button>
                </form>
            </div>
        <?php endif; ?>
        <div class="actions">
            <div class="desktop-actions">
                <a href="<?= $this->getPath('/profile'); ?>" class="action"><i class="icon user"></i></a> 
                <a href="<?= $this->getPath('/wishlist'); ?>" class="action"><i class="icon heart"></i></a>
            </div>
            <a href="<?= $this->getPath('/cart') ?>" class="action"><i class="icon shopping-cart"></i></a>
        </div>
    </div>
</nav>