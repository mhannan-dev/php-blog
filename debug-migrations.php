#!/usr/bin/env php
<?php
/**
 * Temporary debug script — diagnoses why Doctrine can't find migrations.
 * Run: php debug-migrations.php
 * Delete after fixing.
 */

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$migrationsPath = realpath(__DIR__ . '/app/Database/Migrations');

echo "\n=== MIGRATION DIAGNOSTICS ===\n\n";
echo "Migrations path : " . var_export($migrationsPath, true) . "\n";
echo "Directory exists: " . (is_dir($migrationsPath) ? 'YES' : 'NO') . "\n\n";

// Scan files
$files = glob($migrationsPath . '/*.php');
echo "PHP files found (" . count($files) . "):\n";
foreach ($files as $f) {
    echo "  " . basename($f) . "\n";
}
echo "\n";

// Require each file and capture new classes
$classesBefore = get_declared_classes();

foreach ($files as $file) {
    try {
        require_once $file;
        echo "  [OK] required: " . basename($file) . "\n";
    } catch (Throwable $e) {
        echo "  [ERR] " . basename($file) . " => " . $e->getMessage() . "\n";
    }
}

$newClasses = array_diff(get_declared_classes(), $classesBefore);
echo "\nClasses loaded from migrations:\n";
foreach ($newClasses as $class) {
    $parents = class_parents($class);
    $isMigration = in_array('Doctrine\Migrations\AbstractMigration', $parents ?? []);
    echo "  " . ($isMigration ? '[MIGRATION] ' : '[other]     ') . $class . "\n";
}

echo "\n=== END ===\n\n";
