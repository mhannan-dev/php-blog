<?php
/**
 * fix_migrations.php
 * Automatically renames all migration classes to VersionYYYYMMDDHHMMSS format 
 * based on their filename, so Doctrine Migrations executes them in chronological order.
 */

declare(strict_types=1);

$dir = __DIR__ . '/app/Database/Migrations';
$files = glob($dir . '/*.php');

foreach ($files as $file) {
    $basename = basename($file); // e.g., 2026_07_04_000011_create_posts_table.php
    
    // Extract the timestamp part: 2026_07_04_000011
    if (preg_match('/^(\d{4}_\d{2}_\d{2}_\d{6})_/', $basename, $m)) {
        $timestamp = str_replace('_', '', $m[1]); // 20260704000011
        $versionClass = 'Version' . $timestamp;
        
        $content = file_get_contents($file);
        
        // Replace "final class SomeName extends AbstractMigration"
        $content = preg_replace(
            '/final class [A-Za-z0-9_]+ extends AbstractMigration/',
            "final class {$versionClass} extends AbstractMigration",
            $content
        );
        
        file_put_contents($file, $content);
        echo "Updated {$basename} -> {$versionClass}\n";
    }
}

echo "Done fixing migration classes.\n";
