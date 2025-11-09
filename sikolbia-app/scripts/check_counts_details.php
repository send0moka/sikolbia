<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$db = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
try {
    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query('SELECT COUNT(*) AS cnt, MIN(id) AS min_id, MAX(id) AS max_id, MIN(created_at) AS min_created, MAX(created_at) AS max_created FROM iklimoptdpi_data');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "DB: {$db}@{$host}:{$port}\n";
    echo "Count: " . ($row['cnt'] ?? 'NULL') . "\n";
    echo "Min ID: " . ($row['min_id'] ?? 'NULL') . "\n";
    echo "Max ID: " . ($row['max_id'] ?? 'NULL') . "\n";
    echo "Min created_at: " . ($row['min_created'] ?? 'NULL') . "\n";
    echo "Max created_at: " . ($row['max_created'] ?? 'NULL') . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
