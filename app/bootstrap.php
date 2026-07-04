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
if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
    require_once __DIR__ . '/Helpers/Debug.php';
}

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
$post     = $postModel     = new Post($db);
$category = $categoryModel = new Category($db);
$page     = $pageModel     = new Page($db);
$user     = $userModel     = new User($db);
$contact  = $contactModel  = new Contact($db);
$site     = $siteModel     = new Site($db);

// ─── Twig Setup ────────────────────────────────────────────────────────────
$twig = null;
if (class_exists('Twig\Environment')) {
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/views');
    $twig = new \Twig\Environment($loader, [
        'cache' => false, // Set to a path like __DIR__ . '/../cache/twig' in production
        'debug' => ($_ENV['APP_ENV'] ?? 'production') === 'development',
    ]);
    
    // Add global variables to Twig for the header and footer
    $siteInfo = $siteModel->getInfo();
    $twig->addGlobal('siteTitle',  $siteInfo['title'] ?? TITLE);
    $twig->addGlobal('siteSlogan', $siteInfo['slogan'] ?? '');
    $twig->addGlobal('siteLogo',   $siteInfo['logo'] ?? '');
    $twig->addGlobal('socialLinks', $siteModel->getSocialLinks());
    $twig->addGlobal('footerNote', $siteModel->getFooterNote());
    
    $twig->addGlobal('navPages',    $pageModel->getAll());
    $twig->addGlobal('navCats',     $categoryModel->getAll());
    $twig->addGlobal('sidebarCats', $categoryModel->getAll());
    $twig->addGlobal('sidebarPosts', $postModel->getLatest(5));
    $twig->addGlobal('sidebarPages', $pageModel->getAll());
    $twig->addGlobal('footerCats',  $categoryModel->getAll());
    $twig->addGlobal('footerPages', $pageModel->getAll());

    // Basic request data for navigation active states
    $twig->addGlobal('current_page', basename($_SERVER['PHP_SELF']));
    $twig->addGlobal('current_slug', $_GET['slug'] ?? '');
    $twig->addGlobal('current_cat',  $_GET['cat_post'] ?? '');
    $twig->addGlobal('search_query', Format::e($_GET['search'] ?? ''));
    
    // Admin specific globals
    $twig->addGlobal('admin_username', Session::get('userName'));
    $twig->addGlobal('admin_role', Session::get('userRole'));
    
    // Base URL path
    $basePath = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $twig->addGlobal('base_url', $basePath);
}
