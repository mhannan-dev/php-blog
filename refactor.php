<?php
/**
 * refactor.php
 * A helper script to automatically move view files and assets to the new architecture.
 * Run via: php refactor.php
 */

declare(strict_types=1);

echo "Starting architecture refactor...\n\n";

$root = __DIR__;

$dirs = [
    $root . '/resources/views/frontend/inc',
    $root . '/public',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
        echo "Created directory: {$dir}\n";
    }
}

// 1. Move inc/ files
$incFiles = glob($root . '/inc/*');
foreach ($incFiles as $file) {
    if (is_file($file)) {
        $basename = basename($file);
        rename($file, $root . '/resources/views/frontend/inc/' . $basename);
        echo "Moved {$basename} to resources/views/frontend/inc/\n";
    }
}
if (is_dir($root . '/inc')) {
    rmdir($root . '/inc');
}

// 2. Move root views
$views = [
    'post.php' => 'post.php',
    'page.php' => 'page.php',
    'cat_posts.php' => 'cat_posts.php',
    'contact_us.php' => 'contact_us.php',
    'search.php' => 'search.php',
    '404.php' => '404.php',
    'index.php' => 'home.php', // Special rename for index
];

foreach ($views as $source => $dest) {
    if (file_exists($root . '/' . $source)) {
        rename($root . '/' . $source, $root . '/resources/views/frontend/' . $dest);
        echo "Moved {$source} to resources/views/frontend/{$dest}\n";
    }
}

// 3. Move assets
$assets = ['css', 'js', 'images'];
foreach ($assets as $asset) {
    if (is_dir($root . '/' . $asset)) {
        rename($root . '/' . $asset, $root . '/public/' . $asset);
        echo "Moved {$asset}/ to public/{$asset}/\n";
    }
}

// 4. Create new Front Controller (index.php) in root
$frontController = <<<PHP
<?php

/**
 * Nexus CMS - Front Controller
 * All requests are routed through here.
 */

require_once __DIR__ . '/app/bootstrap.php';

// Simple routing based on URI or \$_GET parameters
// For now, we manually route based on the view requested, defaulting to home.
\$view = \$_GET['view'] ?? 'home';

// Basic safety check
\$allowedViews = ['home', 'post', 'page', 'cat_posts', 'contact_us', 'search', '404'];

if (!in_array(\$view, \$allowedViews)) {
    \$view = '404';
}

require_once __DIR__ . "/resources/views/frontend/{\$view}.php";

PHP;

file_put_contents($root . '/index.php', $frontController);
echo "\nGenerated new Front Controller at index.php\n";

echo "\nDone! You can now delete this script (php refactor.php).\n";
