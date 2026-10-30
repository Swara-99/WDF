<?php
$host = 'localhost';
$dbname = 'studenthub';
$username = 'root';
$password = ''; // Change for your local database if required.
$pdo = null;
try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES => false]
    );
} catch (PDOException $ex) {
    // Pages can still show a useful message if the DB has not been imported yet.
}
