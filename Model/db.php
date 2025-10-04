<?php
// Model/db.php
declare(strict_types=1);

/**
 * Global PDO helper. Keeps a single connection.
 * Use: $pdo = db();
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $host = 'localhost';
        $dbname = 'unkein_demo';
        $username = 'root';
        $password = ''; // set to 'root' if you set that earlier

        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    return $pdo;
}