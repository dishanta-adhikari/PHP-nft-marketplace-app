<?php

try {
    $host = $_ENV['DB_HOST'];
    $dbname = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $pass = $_ENV['DB_PASS'];

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $con = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch as assoc array
        PDO::ATTR_EMULATE_PREPARES => false, // real prepares
    ]);

    return $con;
} catch (PDOException $e) {
    $_SESSION['error'] = "Database connection failed: " . $e->getMessage();
    header("Location: " . APP_URL);
    exit;
}
