<?php
$modelService = $this->kernel->getModelService();
$categories = $modelService->find(\App\FetzPetz\Model\Category::class, ['parent' => null]);
?>

<div class="container">
    <section id="mainpage">
        <div class="row">
            <div id="sidebar">
                <?php
                foreach ($categories as $category) {
                    $selected = isset($selectedCategory) && $selectedCategory->id == $category->id; ?>
                    <a href="<?= $this->getPath('/category/' . $category->id); ?>" class="item<?= $selected ? ' selected' : '' ?>">
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
            </div>
            <div id="content">
                <header id="welcome-box">
                    <h1>Welcome to FetzPetz</h1>
                    <h2>Your store for nice shit</h2>
                    <div class="search-box">
                        <form action="<?= $this->getPath('/search') ?>" class="search-inner">
                            <input type="text" name="query" value="<?= $query ?? '' ?>" placeholder="Search for products">
                            <div class="search-button">
                                <i class="icon search"></i>
                            </div>
                        </form>
                    </div>
                </header>
                <div class="products">
                    <?php if (sizeof($products) == 0) : ?>
                        <div class="no-results">
                            <i class="icon sad"></i>
                            <h1>No products found</h1>
                            <h2>We couldn't find any products based on your search</h2>
                            <a href="<?= $this->getPath('/') ?>" class="button">
                                Mainpage
                            </a>
                        </div>
                        <?php endif;
                    $isFirst = true;

                    foreach ($products as $product) :

                        if ($isFirst) { ?>
                            <article class="promoted-product-wrap">
                                <a href="<?= $this->getPath('/product/' . $product->id) ?>" class="product-card" data-id="<?= $product->id ?>">
                                    <div class="image" style="background-image: url(<?= $product->image ?>)"></div>
                                    <div class="data">
                                        <div class="title">
                                            <span><?= $product->name ?></span>
                                            <div class="like-button<?= in_array($product->id, $wishlist) ? ' active' : '' ?>">
                                                <i class="icon heart"></i>
                                            </div>
                                        </div>
                                        <span class="price"><?= $product->cost_per_item ?> €</span>
                                        <div class="description"><?= nl2br($product->description) ?></div>
                                    </div>
                                </a>
                            </article>
                    <?php $isFirst = false;
                        } else
                            $this->renderComponent("components/productCard.php", ["product" => $product]);
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>