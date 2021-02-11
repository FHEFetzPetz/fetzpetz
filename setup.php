<?php


$config = [];

require_once "./src/config/database.php";

$dbConfig = $config["database"];

echo "\033[01;31m  _____      _         ____        _  \n|  ___|___ | |_  ____|  _ \  ___ | |_  ____\n| |_  / _ \| __||_  /| |_) |/ _ \| __||_  /\n|  _||  __/| |_  / / |  __/|  __/| |_  / / \n|_|   \___| \__|/___||_|    \___| \__|/___| ... Shop Setup\033[0m\n\nProject by \033[01;33mSaskia Wohlers\033[0m, \033[01;33mDirk Hoffmann\033[0m & \033[01;33mLuca Voges\033[0m\n\n";


function awaitInput() {
    $handle = fopen("php://stdin","r");
    $line = fgets($handle);
    fclose($handle);
    return trim($line);
}

echo "Create database '" . $dbConfig["database"] . '\'? [yes/no] ';
$result = awaitInput();
$mysqli = null;

if(trim($result) == 'yes') {
    echo "\n\033[01;33mCreating database '" . $dbConfig["database"] . "'...\033[0m";

    $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], null, $dbConfig['port']);

    if($mysqli->connect_errno)
        die('Connection to mysql-server failed: (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);

    $queryResult = $mysqli->query('CREATE DATABASE ' . $dbConfig["database"]);
    if(!$queryResult) die("\n\n\033[01;31mFailed to create database: " . $mysqli->error . "\033[0m");
    else echo "\n\n\033[01;32mDatabase '" . $dbConfig["database"] . "' created.\033[0m\n";

    $mysqli->select_db($dbConfig['database']);

    echo "\n\033[01;33mCreating tables...\033[0m\n";

    function createTable($tableName, $query, $mysqli) {
        echo "\n\033[01;35mCreating Table $tableName...\033[0m";
        $queryResult = $mysqli->query($query);

        if(!$queryResult) echo "\n\n\033[01;31mFailed to create table $tableName: " . $mysqli->error . "\033[0m";
    }

    createTable('user',                     'CREATE TABLE IF NOT EXISTS user (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname VARCHAR(150) NOT NULL, lastname VARCHAR(150) NOT NULL, birthday DATE NOT NULL, password_hash VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, email_verified TINYINT(1) DEFAULT 0, email_verification_hash VARCHAR(20), active TINYINT(1) DEFAULT 1, created_at DATETIME NOT NULL)', $mysqli);
    createTable('payment_reference',        'CREATE TABLE IF NOT EXISTS payment_reference (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, payment_method VARCHAR(100) NOT NULL, payment_data TEXT, created_at DATETIME NOT NULL)', $mysqli);
    createTable('address',                  'CREATE TABLE IF NOT EXISTS address (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname VARCHAR(150) NOT NULL, lastname VARCHAR(150) NOT NULL, street VARCHAR(150) NOT NULL, zip VARCHAR(15) NOT NULL, city VARCHAR(100) NOT NULL, state VARCHAR(100), country VARCHAR(2) NOT NULL, phone_number VARCHAR(30) NOT NULL)', $mysqli);
    createTable('category',                 'CREATE TABLE IF NOT EXISTS category (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, created_by INT NOT NULL, name VARCHAR(100) NOT NULL, description TEXT NOT NULL, image VARCHAR(100) NOT NULL, active TINYINT(1) DEFAULT 1, parent INT, CONSTRAINT fk_category_user_id FOREIGN KEY (created_by) REFERENCES user(id), CONSTRAINT fk_category_parent FOREIGN KEY (parent) REFERENCES category(id))', $mysqli);
    createTable('product',                  'CREATE TABLE IF NOT EXISTS product (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, created_by INT NOT NULL, name VARCHAR(100) NOT NULL, description TEXT NOT NULL, image VARCHAR(100) NOT NULL, extra_attributes TEXT NOT NULL, cost_per_item DECIMAL(7,2) NOT NULL, availability INT NOT NULL, active TINYINT(1) DEFAULT 1, search_tags TEXT NOT NULL, CONSTRAINT fk_product_created_by FOREIGN KEY (created_by) REFERENCES user(id))', $mysqli);
    createTable('administration_access',    'CREATE TABLE IF NOT EXISTS administration_access (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, created_by INT, active TINYINT(1) DEFAULT 1, created_at DATETIME NOT NULL, CONSTRAINT fk_administration_access_user_id FOREIGN KEY (user_id) REFERENCES user(id), CONSTRAINT fk_administration_access_user_created_by FOREIGN KEY (created_by) REFERENCES user(id))', $mysqli);
    createTable('order_object',             'CREATE TABLE IF NOT EXISTS order_object (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, payment_reference_id INT NOT NULL, shipping_address_id INT NOT NULL, billing_address_id INT, order_status VARCHAR(100) DEFAULT "pending_payment", shipment_data TEXT, CONSTRAINT fk_order_user_id FOREIGN KEY (user_id) REFERENCES user(id), CONSTRAINT fk_order_payment_reference_id FOREIGN KEY (payment_reference_id) REFERENCES payment_reference(id), CONSTRAINT fk_order_shipping_address_id FOREIGN KEY (shipping_address_id) REFERENCES address(id), CONSTRAINT fk_order_billing_address_id FOREIGN KEY (billing_address_id) REFERENCES address(id))', $mysqli);
    createTable('order_item',               'CREATE TABLE IF NOT EXISTS order_item (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, order_id INT NOT NULL, product_id INT NOT NULL, amount INT NOT NULL, cost_per_item DECIMAL(7,2) NOT NULL, item_data TEXT, CONSTRAINT fk_order_item_order_id FOREIGN KEY (order_id) REFERENCES order_object(id), CONSTRAINT fk_order_item_product_id FOREIGN KEY (product_id) REFERENCES product(id))', $mysqli);
    createTable('wishlist_item',            'CREATE TABLE IF NOT EXISTS wishlist_item (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, product_id INT NOT NULL, user_id INT NOT NULL, CONSTRAINT fk_wishlist_product_id FOREIGN KEY (product_id) REFERENCES product(id), CONSTRAINT fk_wishlist_user_id FOREIGN KEY (user_id) REFERENCES user(id))', $mysqli);
    createTable('product_category',         'CREATE TABLE IF NOT EXISTS product_category (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, product_id INT NOT NULL, category_id INT NOT NULL, CONSTRAINT fk_product_category_product_id FOREIGN KEY (product_id) REFERENCES product(id), CONSTRAINT fk_product_category_category_id FOREIGN KEY (category_id) REFERENCES category(id))', $mysqli);
} else {
    $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig["database"], $dbConfig['port']);

    if($mysqli->connect_errno)
        die('Connection to mysql-server failed: (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
}

echo "\n\nCreate \033[01;31mAdmin-User\033[0m? [yes/no] ";
$result = awaitInput();

if(trim($result) == 'yes') {
    echo "Enter \033[01;31mAdmin-Email Address\033[0m: ";
    $email = awaitInput();

    echo "Enter \033[01;31mFirst- & Last-Name\033[0m [John Doe]: ";
    $fullName = awaitInput();

    echo "\nCreating \033[01;31mAdmin\033[0m\033[01;33m $email\033[0m with name\033[01;33m $fullName\033[0m...";

    $nameExploded = explode(' ', $fullName);
    $firstName = $nameExploded[0];
    $lastName = sizeof($nameExploded) > 1 ? $nameExploded[1] : '';

    $randomPassword = uniqid() . date('-Y-m-d');
    $passwordHash = password_hash($randomPassword, PASSWORD_BCRYPT);

    $queryResult = $mysqli->query(
        'INSERT INTO user (firstname, lastname, birthday, password_hash, email, email_verified, created_at)
        VALUES (\'' . $firstName . '\', \'' . $lastName . '\', \'1970-01-01 12:00:00\', \'' . $passwordHash . '\', \'' . $email . '\', 1, \'' . date('Y-m-d H:i:s') . '\')
    ');

    if(!$queryResult) echo "\n\n\033[01;31mFailed to create User: " . $mysqli->error . "\033[0m";

    $userId = $mysqli->insert_id;

    $queryResult = $mysqli->query(
        'INSERT INTO administration_access (user_id, created_at)
        VALUES (' . $userId . ', \'' . date('Y-m-d H:i:s') . '\')
    ');

    if(!$queryResult) echo "\n\n\033[01;31mFailed to create Administrator: " . $mysqli->error . "\033[0m";

    echo "\n\n\033[01;32mAdministrator '" . $dbConfig["database"] . "' created.\033[0m\n\n";
    echo "\033[01;33mE-Mail Address: " . $email . "\033[0m\n";
    echo "\033[01;33mPassword:       " . $randomPassword . "\033[0m\n";
    echo "\n\033[01;36mFor security reasons please change your password as soon as possible!\033[0m";
}

echo "\n\nCreate Test-Data? [yes/no] ";
$result = awaitInput();

if(trim($result) == 'yes') {
    echo "\n\033[01;36mWork in Progress (sorry)!\033[0m";
}

echo "\n\n\033[01;33mSetup Completed!\033[0m";