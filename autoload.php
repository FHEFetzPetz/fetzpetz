<?php

const targets = [
    "src/core/*.php",
    "src/components/*.php",
    "src/services/*.php",
    "src/models/*.php",
    "src/controller/*.php",
    "src/Kernel.php"
];

//looping through php necessary files
foreach(targets as $target)
    foreach(glob($target) as $class)
        require_once $class;