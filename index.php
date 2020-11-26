<?php

$config = [
    'appDir' => __DIR__,
    'environment' => 'dev',
    'htaccessRouting' => false
];

require_once './src/config/base.php';
require_once './src/Kernel.php';

$kernel = new Kernel($config);
$kernel->getRequestHandler()->handleRequest();

$kernel->getDatabaseHandler()->closeConnection();