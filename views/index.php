<div class="container">
    <section id="mainpage">
        <div class="row">
            <div id="sidebar">
                <?php $this->renderComponent("components/sidebarContents.php"); ?>
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