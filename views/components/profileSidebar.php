<div id="sidebar">
    <?php if ($this->isAuthenticated()) : ?>
        <div class="item">
            <span>Orders</span>
        </div>
    <?php endif ?>
    <a href="<?= $this->getPath('/cart') ?>" class="item">
        <span>Cart</span>
    </a>
    <?php if ($this->isAuthenticated()) : ?>
        <a href="<?= $this->getPath('/wishlist'); ?>" class="item">
            <span>Wishlist</span>
        </a>
        <div class="item">
            <span>Settings</span>
        </div>
        <a href="<?= $this->getPath('/logout'); ?>" class="item margin-top">
            <span>Logout</span>
        </a>
    <?php else : ?>
        <a href="<?= $this->getPath('/login'); ?>" class="item margin-top">
            <span>Log in</span>
        </a>
        <a href="<?= $this->getPath('/signup'); ?>" class="item color">
            <span>Sign up</span>
        </a>
    <?php endif ?>
</div>