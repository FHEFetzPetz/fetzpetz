<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $title ?? $this->getConfig()["meta"]["title"] ?></title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="apple-touch-icon" sizes="180x180" href="assets/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/icons/favicon-16x16.png">
        <link rel="manifest" href="assets/icons/site.webmanifest">
        <link rel="mask-icon" href="assets/icons/safari-pinned-tab.svg" color="#333333">
        <link rel="shortcut icon" href="assets/icons/favicon.ico">
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
        <?php
            if(!isset($navigation) || $navigation) $this->renderComponent("components/navigation.php");
            $this->renderView()
        ?>
        <script>
            <?php if(!isset($navigation) || $navigation) { ?>
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
            <?php } ?>
        </script>
    </body>
</html>