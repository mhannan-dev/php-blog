<?php
/**
 * fix_admin_url.php
 * Moves the admin panel back to the root directory so the URL `/admin` works properly
 * and all the relative CSS/JS paths resolve correctly on the built-in PHP server.
 */

declare(strict_types=1);

$source = __DIR__ . '/resources/views/admin';
$dest   = __DIR__ . '/admin';

if (is_dir($source)) {
    rename($source, $dest);
    echo "Successfully moved admin panel to the root directory.\n";
    echo "You can now access it at: http://localhost:8888/admin\n";
} else {
    if (is_dir($dest)) {
        echo "Admin panel is already at the root directory.\n";
    } else {
        echo "Could not find the admin panel at $source\n";
    }
}
