<?php
// Model/db.php

declare(strict_types=1);

/**
 * Global PDO helper function.
 * Maintains a single connection for the entire app.
 * 
 * Usage example:
 *   $pdo = db();
 */
function db(): PDO
{
    // 'static' means the variable persists between function calls
    static $pdo = null;

    // If no connection exists yet, create it
    if ($pdo === null) {
        $host = 'localhost';
        $dbname = 'unkein_demo';
        $username = 'root';
        $password = ''; // empty password (hit Enter in MySQL CLI)

        // DSN: Data Source Name — how PDO connects to MySQL
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

        // Create PDO instance with proper options
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    // Return the existing PDO object
    return $pdo;
}