<?php


/**
 * Establish a connection.
 * @return PDO|null the PDO object represent connection to database.
 */
function connectDb()
{
    global $dbConfig;
    try {
        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
        return new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

$dbConfig = [
    'host' => $_ENV['DB_HOST'],
    'dbname' => $_ENV['DB_NAME'],
    'charset' => $_ENV['DB_CHARSET'],
    'user' => $_ENV['DB_USER'],
    'pass' => $_ENV['DB_PASS']
];


