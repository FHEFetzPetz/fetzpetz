<div class="container">
    <section id="wishlist">
        <div class="row">
            <?php $this->renderComponent("components/profileSidebar.php"); ?>
            <div id="content">
                <div class="box">
                    <h1>Wishlist</h1>
                    <div class="items">
                        <?php if (sizeof($items) == 0) : ?>
                            <div class="empty-message">
                                <i class="icon heart"></i>
                                <span>Your Wishlist is empty</span>
                                <a href="<?= $this->getPath('/') ?>">Go Shopping</a>
                            </div>
                        <?php else : ?>


                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>