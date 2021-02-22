<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $title ?? $this->getConfig()["meta"]["title"] ?></title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        

        <link rel="apple-touch-icon" sizes="180x180" href="/assets/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/icons/favicon-16x16.png">
        <link rel="manifest" href="/assets/icons/site.webmanifest">
        <link rel="mask-icon" href="/assets/icons/safari-pinned-tab.svg" color="#333333">
        <link rel="shortcut icon" href="/assets/icons/favicon.ico">
        <meta name="msapplication-TileColor" content="#333333">
        <meta name="msapplication-config" content="icons/browserconfig.xml">
        <meta name="theme-color" content="#333333">

        <meta name="robots" content="nofollow,noindex">

        <link rel="stylesheet" href="/assets/fonts/OpenSans/font.css">
        <link rel="stylesheet" href="/assets/css/fontawesome.css">
        <link rel="stylesheet" href="/assets/css/base.css">
        <link rel="stylesheet" href="/assets/css/administration.css">
        <link rel="stylesheet" href="/assets/css/profileSidebar.css">

        <?php
            foreach($extraHeaderFields as $item) {
                switch($item['type']) {
                    case 'stylesheet': ?><link rel="stylesheet" href="<?= $item['href'] ?>"><?php break;
                    case 'script': ?><script rel="stylesheet" src="<?= $item['src'] ?>"></script><?php break;
                }
            }
        ?>

    </head>
    <body>
        <?php
            if(!isset($navigation) || $navigation) $this->renderComponent("components/navigation.php");
        ?>
        <div id="notifications">
            <?php foreach ($this->kernel->getNotificationService()->getNotifications() as $notification) : ?>
                <div class="notification" data-type="<?= $notification["type"] ?>">
                    <div class="title"><?= $notification["title"] ?></div>
                    <span><?= $notification["message"] ?></span>
                    <div class="close"><i class="icon times"></i></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="container">
            <section id="administration">
                <div class="row">
                    <div id="sidebar">
                        <a class="item chin">
                            <span>FetzPetz Administration</span>
                            <i class="icon chevron-up"></i>
                        </a>
                        <a href="<?= $this->getPath('/admin/users') ?>" class="item margin-top">
                            <span>Users</span>
                        </a>
                        <a href="<?= $this->getPath('/admin/categories') ?>" class="item">
                            <span>Categories</span>
                        </a>
                        <a href="<?= $this->getPath('/admin/products') ?>" class="item">
                            <span>Products</span>
                        </a>
                        <a href="<?= $this->getPath('/admin/orders') ?>" class="item">
                            <span>Orders</span>
                        </a>
                    </div>
                    <div id="content">
                        <?php $this->renderView() ?>
                    </div>
                </div>
            </section>
        </div>
        <div id="scroll-top"><i class="icon chevron-up"></i></div>
        <div id="script-data" class="hidden-data" data-value='<?= json_encode([
            'wishlistRemove' => $this->getPath('/wishlist/remove/'),
            'wishlistAdd' => $this->getPath('/wishlist/add/'),
            'wishlistRemoveRedirect' => $this->getPath('/wishlist/remove/redirect/'),
            'wishlistAddRedirect' => $this->getPath('/wishlist/add/redirect/'),
            'navigation' => !isset($navigation) || $navigation,
            'slim' => isset($slim) && $slim,
            'authenticated' => $this->isAuthenticated()
        ], JSON_UNESCAPED_SLASHES); ?>'></div>
        <script src="/assets/js/base.js"></script>
    </body>
</html>