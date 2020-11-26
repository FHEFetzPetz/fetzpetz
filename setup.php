<?php


$config = [];

require_once "./src/config/database.php";

$dbConfig = $config["database"];

echo "Setup for Project database\n\nCreate database '" . $dbConfig["database"] . '\'? [yes/no] ';

$handle = fopen("php://stdin","r");
$line = fgets($handle);

if(trim($line) !== 'yes') {
    echo "\nAbort action!\n\nGoodbye";
    exit;
}

fclose($handle);

echo "\nCreating database '" . $dbConfig["database"] . '\'...';

$mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], null, $dbConfig['port']);

if($mysqli->connect_errno)
    die('Connection to mysql-server failed: (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);

$queryResult = $mysqli->query('CREATE DATABASE ' . $dbConfig["database"]);

if($queryResult)
    echo "\n\nDatabase '" . $dbConfig["database"] . "' created.\n\nGoodbye";
else
    echo "\n\nFailed to create database '" . $dbConfig["database"] . '\': ' . $mysqli->error;


