<?php

/**
 * Database Seeder Runner
 * 
 * This script initializes the environment, establishes a dedicated PDO 
 * connection, and runs the DatabaseSeeder orchestration class.
 */

// 1. Require Composer Autoloader
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Database\DatabaseSeeder;

// 2. Load Environment Variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// 3. Establish PDO Connection
try {
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $dbname = $_ENV['DB_NAME'] ?? 'blg';
    $user = $_ENV['DB_USER'] ?? 'root';
    $pass = $_ENV['DB_PASS'] ?? '';

    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);
    
    echo "Connected to the database successfully.\n";

} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage() . "\n");
}

// 4. Instantiate and Run the Master Seeder
try {
    $seeder = new DatabaseSeeder($pdo);
    $seeder->run();
} catch (Throwable $e) {
    die("Seeding failed: " . $e->getMessage() . "\n");
}
