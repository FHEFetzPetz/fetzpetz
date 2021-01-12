<?php

$config = [
    'appDir' => __DIR__,
    'environment' => 'dev',
    'htaccessRouting' => true,
    'subDirectory' => ''
];

session_start();

require_once './src/config/base.php';
require_once "autoload.php";

$kernel = new \App\FetzPetz\Kernel($config);
$kernel->getRequestService()->handleRequest();

$kernel->getDatabaseService()->closeConnection();