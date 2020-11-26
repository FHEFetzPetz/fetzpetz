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

$mysqli->select_db($dbConfig['database']);

$queryResult = $mysqli->query('CREATE TABLE customer (id INT NOT NULL PRIMARY KEY, firstname VARCHAR(150), lastname VARCHAR(150), birthday DATE, password VARCHAR(250), email VARCHAR(150))');

if($queryResult)
    echo "\n\nDatabase '" . $dbConfig["database"] . "' created.\n\nGoodbye";
else
    echo "\n\nFailed to create database '" . $dbConfig["database"] . '\': ' . $mysqli->error;


