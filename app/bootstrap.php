<?php

/**
 * bootstrap.php — single application entry point.
 *
 * Every page (public and admin) includes this ONE file instead of
 * 3–4 separate require_once calls. It sets up the environment,
 * autoloads all classes, starts the session, and provides model instances.
 */

// ─── Autoloader & Environment ──────────────────────────────────────────────
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();
}

// ─── Core ──────────────────────────────────────────────────────────────────
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Core/Database.php';
require_once __DIR__ . '/Core/Session.php';
require_once __DIR__ . '/Helpers/Format.php';

// ─── Models ────────────────────────────────────────────────────────────────
require_once __DIR__ . '/Models/Post.php';
require_once __DIR__ . '/Models/Category.php';
require_once __DIR__ . '/Models/Page.php';
require_once __DIR__ . '/Models/User.php';
require_once __DIR__ . '/Models/Contact.php';
require_once __DIR__ . '/Models/Site.php';

// ─── Bootstrap ─────────────────────────────────────────────────────────────
Session::init();

$db = Database::getInstance();

// ─── Model Instances ───────────────────────────────────────────────────────
// Available to every page as $post, $category, etc.
$post     = $postModel     = new Post($db);
$category = $categoryModel = new Category($db);
$page     = $pageModel     = new Page($db);
$user     = $userModel     = new User($db);
$contact  = $contactModel  = new Contact($db);
$site     = $siteModel     = new Site($db);
