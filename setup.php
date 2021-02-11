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

$queryResult = $mysqli->query('CREATE TABLE IF NOT EXISTS user (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname VARCHAR(150) NOT NULL, lastname VARCHAR(150) NOT NULL, birthday DATE NOT NULL, password_hash VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, email_verified TINYINT(1) DEFAULT 0, email_verification_hash VARCHAR(20), active TINYINT(1) DEFAULT 1, created_at DATETIME NOT NULL)');$queryResult = $mysqli->query('CREATE TABLE IF NOT EXISTS payment_reference (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, payment_method VARCHAR(100) NOT NULL, payment_data TEXT, created_at DATETIME NOT NULL)');
$queryResult = $mysqli->query('CREATE TABLE IF NOT EXISTS address (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname VARCHAR(150) NOT NULL, lastname VARCHAR(150) NOT NULL, street VARCHAR(150) NOT NULL, zip VARCHAR(15) NOT NULL, city VARCHAR(100) NOT NULL, state VARCHAR(100), country VARCHAR(2) NOT NULL, phone_number VARCHAR(30) NOT NULL)');
$queryResult = $mysqli->query('CREATE TABLE IF NOT EXISTS category (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, created_by INT NOT NULL, name VARCHAR(100) NOT NULL, description TEXT NOT NULL, image VARCHAR(100) NOT NULL, active TINYINT(1) DEFAULT 1, parent INT, CONSTRAINT fk_category_user_id FOREIGN KEY (created_by) REFERENCES user(id), CONSTRAINT fk_category_parent FOREIGN KEY (parent) REFERENCES category(id))');
$queryResult = $mysqli->query('CREATE TABLE IF NOT EXISTS product (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, created_by INT NOT NULL, name VARCHAR(100) NOT NULL, description TEXT NOT NULL, image VARCHAR(100) NOT NULL, extra_attributes TEXT NOT NULL, cost_per_item DECIMAL(7,2) NOT NULL, availability INT NOT NULL, active TINYINT(1) DEFAULT 1, search_tags TEXT NOT NULL, CONSTRAINT fk_product_created_by FOREIGN KEY (created_by) REFERENCES user(id))');
$queryResult = $mysqli->query('CREATE TABLE IF NOT EXISTS administration_access (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, created_by INT, active TINYINT(1) DEFAULT 1, created_at DATETIME NOT NULL, CONSTRAINT fk_administration_access_user_id FOREIGN KEY (user_id) REFERENCES user(id), CONSTRAINT fk_administration_access_user_created_by FOREIGN KEY (created_by) REFERENCES user(id))');
$queryResult = $mysqli->query('CREATE TABLE IF NOT EXISTS order_object (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, payment_reference_id INT NOT NULL, shipping_address_id INT NOT NULL, billing_address_id INT, order_status VARCHAR(100) DEFAULT "pending_payment", shipment_data TEXT, CONSTRAINT fk_order_user_id FOREIGN KEY (user_id) REFERENCES user(id), CONSTRAINT fk_order_payment_reference_id FOREIGN KEY (payment_reference_id) REFERENCES payment_reference(id), CONSTRAINT fk_order_shipping_address_id FOREIGN KEY (shipping_address_id) REFERENCES address(id), CONSTRAINT fk_order_billing_address_id FOREIGN KEY (billing_address_id) REFERENCES address(id))');
$queryResult = $mysqli->query('CREATE TABLE IF NOT EXISTS order_item (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, order_id INT NOT NULL, product_id INT NOT NULL, amount INT NOT NULL, cost_per_item DECIMAL(7,2) NOT NULL, item_data TEXT, CONSTRAINT fk_order_item_order_id FOREIGN KEY (order_id) REFERENCES order_object(id), CONSTRAINT fk_order_item_product_id FOREIGN KEY (product_id) REFERENCES product(id))');
$queryResult = $mysqli->query('CREATE TABLE IF NOT EXISTS wishlist_item (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, product_id INT NOT NULL, user_id INT NOT NULL, CONSTRAINT fk_wishlist_product_id FOREIGN KEY (product_id) REFERENCES product(id), CONSTRAINT fk_wishlist_user_id FOREIGN KEY (user_id) REFERENCES user(id))');
$queryResult = $mysqli->query('CREATE TABLE IF NOT EXISTS product_category (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, product_id INT NOT NULL, category_id INT NOT NULL, CONSTRAINT fk_product_category_product_id FOREIGN KEY (product_id) REFERENCES product(id), CONSTRAINT fk_product_category_category_id FOREIGN KEY (category_id) REFERENCES category(id))');

if($queryResult)
    echo "\n\nDatabase '" . $dbConfig["database"] . "' created.\n\nGoodbye";
else
    echo "\n\nFailed to create database '" . $dbConfig["database"] . '\': ' . $mysqli->error;


