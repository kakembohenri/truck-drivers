<?php

$serverName = "localhost";
$username = "root";
$password = "";

try {

    $connection = new PDO("mysql:host=$serverName;dbname=truck", $username, $password);

    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {

    echo " Connection failed: " . $error->getMessage();
}
