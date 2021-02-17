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

        <link rel="stylesheet" href="/assets/fonts/OpenSans/font.css">
        <link rel="stylesheet" href="/assets/css/fontawesome.css">
        <link rel="stylesheet" href="/assets/css/base.css">

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
        <?php if(!isset($navigation) || $navigation) $this->renderComponent("components/navigation.php"); ?>
        <div id="notifications">
            <?php foreach($this->kernel->getNotificationService()->getNotifications() as $notification): ?>
            <div class="notification" data-type="<?= $notification["type"] ?>">
                <div class="title"><?= $notification["title"] ?></div>
                <span><?= $notification["message"] ?></span>
                <div class="close"><i class="icon times"></i></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php $this->renderView() ?>
        <div id="scroll-top"><i class="icon chevron-up"></i></div>
        <script>
            scrollTopButton = document.getElementById("scroll-top");

            function toggleLikeProduct(id, item) {
                var request = new XMLHttpRequest();
                const url = item.classList.contains('active') ? ("<?= $this->getPath('/wishlist/remove/') ?>" + id) : ("<?= $this->getPath('/wishlist/add/') ?>" + id);
                request.open("GET", url);
                request.addEventListener('load', function() {
                    if (request.status === 200) {
                        item.classList.toggle('active');
                    } else
                        alert("nicht so nice");
                });

                request.send();
            }

            document.addEventListener('DOMContentLoaded', function() {
                this.querySelectorAll('.product-card .like-button').forEach(function(item) {
                    item.addEventListener('click', function(event) {
                        event.preventDefault();

                        const id = item.closest('.product-card').getAttribute('data-id');
                        toggleLikeProduct(id, item);
                    })
                });
            })


            window.onscroll = function() {scrollFunction()};

            function scrollFunction() {
                if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                    scrollTopButton.classList.add("reveal");
                } else {
                    scrollTopButton.classList.remove("reveal");
                }
            }

            scrollTopButton.addEventListener("click", function() {
                document.body.scrollIntoView({behavior: 'smooth', block: 'start'})
            });

            <?php if(!isset($navigation) || $navigation): ?>
            document.querySelector("#navigation .menu-toggle").addEventListener("click",function() {
                document.getElementById("navigation").classList.add("reveal-menu");
            });

            document.querySelector("#navigation .menu .close").addEventListener("click",function() {
                document.getElementById("navigation").classList.remove("reveal-menu");
            });

            document.querySelector("#navigation .menu-overlay").addEventListener("click",function() {
                document.getElementById("navigation").classList.remove("reveal-menu");
            });

            document.querySelector("#navigation .menu").addEventListener("click",function(e) {
                e.stopPropagation();
            });

            <?php if(!isset($slim) || !$slim): ?>
            document.querySelector("#navigation .search-box .search-button").addEventListener("click",function(e) {
                if(window.innerWidth < 900)
                    document.getElementById("navigation").classList.add("search");
            });
            <?php endif ?>
            <?php endif ?>

            document.querySelectorAll('#notifications .notification .close').forEach(function(item) {
                item.addEventListener('click', function() {
                    this.closest('.notification').remove();
                });
            });

            function pushNotification(title, message, type) {
                const notification = document.createElement('div');
                notification.classList.add('notification');
                notification.innerHTML = "<div class='title'>"+title+"</div><span>"+message+"</span><div class='close'><i class='icon times'></i></div>";
                notification.setAttribute('data-type', type);

                notification.addEventListener('click', function() {
                    this.closest('.notification').remove();
                });

                document.getElementById('notifications').appendChild(notification);
            }
        </script>
    </body>
</html>