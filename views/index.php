<html>
    <head>
        <title>Test</title>
    </head>
    <body>
        Relative: <a href="<?=$this->getPath("/customer")?>"><?=$this->getPath("/customer")?></a><br>
        Absolute: <a href="<?=$this->getAbsolutePath("/customer")?>"><?=$this->getAbsolutePath("/customer")?></a>
    </body>
</html>