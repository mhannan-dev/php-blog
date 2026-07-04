<?php

// ─── Database ────────────────────────────────────────────────────────────────
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'blg');

// ─── Site ────────────────────────────────────────────────────────────────────
define('TITLE',     $_ENV['TITLE']     ?? 'Nexus CMS');
define('META_DESC', $_ENV['META_DESC'] ?? 'A blog developed by Muhammad Hannan using PHP & MySQL.');
define('KEYWORDS',  $_ENV['KEYWORDS']  ?? 'PHP, Laravel, Vue JS, WordPress, plugin');

// ─── Environment ─────────────────────────────────────────────────────────────
// Set to 'production' on a live server to suppress error output.
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');

if (APP_ENV === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}
