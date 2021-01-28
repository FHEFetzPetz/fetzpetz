<?php
$categories = $this->kernel->getModelService()->find(\App\FetzPetz\Model\Category::class);
?>

<div class="container">
    <section id="mainpage">
        <div class="row">
            <div id="sidebar">
                <?php
                foreach($categories as $category) { ?>
                    <div class="item">
                        <span><?= $category->__get('name') ?></span>
                        <i class="icon chevron-right"></i>
                    </div>
                <?php }
                ?>
                <a href="<?= $this->getPath('/wishlist') ?>" class="item">
                    <span>Wishlist</span>
                </a>
                <a href="<?= $this->getPath('/cart') ?>" class="item">
                    <span>Cart</span>
                </a>
                <?php if($this->isAuthenticated()): ?>
                <a href="<?= $this->getPath('/logout'); ?>" class="item margin-top">
                    <span>Logout</span>
                </a>
                <div class="item color">
                    <span>My Profile</span>
                </div>
                <?php else: ?>
                <a href="<?= $this->getPath('/login'); ?>" class="item margin-top">
                    <span>Log in</span>
                </a>
                <a href="<?= $this->getPath('/signup'); ?>" class="item color">
                    <span>Sign up</span>
                </a>
                <?php endif ?>
            </div>
            <div id="content">
                <header id="welcome-box">
                    <h1>Welcome to FetzPetz</h1>
                    <h2>Your store for nice shit</h2>
                    <div class="search-box">
                        <div class="search-inner">
                            <input type="text" placeholder="Search for products">
                            <div class="search-button">
                                <i class="icon search"></i>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="products">

                    <?php
                    $isFirst = true;

                    foreach($products as $product):

                    if($isFirst) { ?>
                        <article class="promoted-product-wrap">
                            <a href="<?= $this->getPath('/product/' . $product->__get('id')) ?>" class="product-card">
                                <div class="image" style="background-image: url(<?= $product->__get("image") ?>)"></div>
                                <div class="data">
                                    <div class="title">
                                        <span><?= $product->__get("name") ?></span>
                                        <div class="like-button">
                                            <i class="icon heart"></i>
                                        </div>
                                    </div>
                                    <span class="price"><?= $product->__get("cost_per_item") ?> €</span>
                                    <div class="description"><?= nl2br($product->__get("description")) ?></div>
                                </div>
                            </a>
                        </article>
                    <?php $isFirst = false;
                    } else
                        $this->renderComponent("components/productCard.php", ["product"=>$product]);
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>