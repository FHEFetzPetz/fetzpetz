<?php

//initial configuration
$config = [
    'appDir' => __DIR__,
    'environment' => 'dev',
    'htaccessRouting' => true, //if true site can be reached by [website]/subpage instead of [website]?page=subpage
    'subDirectory' => ''
];

session_start();

//loading other configuration files and autoloading all necessary php classes
require_once './src/config/base.php';
require_once "autoload.php";

//initializing kernel and handling request
$kernel = new \App\FetzPetz\Kernel($config);
$kernel->getRequestService()->handleRequest();

//finishing kernel after rendering
$kernel->getDatabaseService()->closeConnection();