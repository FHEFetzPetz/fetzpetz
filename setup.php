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

echo "\nCreating tables...\n";

$queryResult = $mysqli->query('CREATE TABLE user (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname VARCHAR(150), lastname VARCHAR(150), birthday DATE, password VARCHAR(250), email VARCHAR(150))');

$queryResult = $mysqli->query('CREATE TABLE paymentReference (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, payment_method VARCHAR(100), payment_data TEXT, created_at DATETIME)');

$queryResult = $mysqli->query('CREATE TABLE address (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname VARCHAR(150), lastname VARCHAR(150), street VARCHAR(150), zip VARCHAR(15), city VARCHAR(100), state VARCHAR(100), country VARCHAR(2), phoneNumber VARCHAR(30))');

$queryResult = $mysqli->query('CREATE TABLE category (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, created_by INT, name VARCHAR(100), description TEXT, image VARCHAR(100), active TINYINT(1) DEFAULT 1)');

$queryResult = $mysqli->query('CREATE TABLE product (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, created_by INT, name VARCHAR(100), description TEXT, product_images TEXT, extra_attributes TEXT, cost_per_item DECIMAL(7,2), availability INT, active TINYINT(1) DEFAULT 1, search_tags TEXT)');

$queryResult = $mysqli->query('CREATE TABLE administrationAccess (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, user_id INT, created_by INT, active TINYINT(1) DEFAULT 1, created_at DATETIME)');

$queryResult = $mysqli->query('CREATE TABLE order (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, user_id INT, payment_reference_id INT, shipping_address_id INT, billing_address_id INT, order_status VARCHAR(100) DEFAULT "pending_payment", shipment_data TEXT)');

$queryResult = $mysqli->query('CREATE TABLE orderItem (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, order_id INT, product_id INT, amount INT, cost_per_item DECIMAL(7,2), item_data TEXT)');

if($queryResult)
    echo "\n\nDatabase '" . $dbConfig["database"] . "' created.\n\nGoodbye";
else
    echo "\n\nFailed to create database '" . $dbConfig["database"] . '\': ' . $mysqli->error;


