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
        'cache' => false,
        'debug' => ($_ENV['APP_ENV'] ?? 'production') === 'development',
    ]);

    // ─── Helper: Convert mysqli_result → plain array ────────────────────────
    $toArray = function ($result): array {
        if (!$result || !($result instanceof mysqli_result)) {
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC) ?: [];
    };

    // ─── Site Info ──────────────────────────────────────────────────────────
    $siteInfo = $siteModel->getInfo();
    $twig->addGlobal('siteTitle',       $siteInfo['title']  ?? TITLE);
    $twig->addGlobal('siteSlogan',      $siteInfo['slogan'] ?? '');
    $twig->addGlobal('siteLogo',        $siteInfo['logo']   ?? '');
    $twig->addGlobal('metaTitle',       Format::title() . ' — ' . ($siteInfo['title'] ?? TITLE));
    $twig->addGlobal('metaDescription', META_DESC);
    $twig->addGlobal('metaKeywords',    KEYWORDS);
    $twig->addGlobal('socialLinks', $siteModel->getSocialLinks());
    $twig->addGlobal('footerNote',  $siteModel->getFooterNote());

    // ─── Navigation / Sidebar globals (as plain arrays) ─────────────────────
    $allPages = $toArray($pageModel->getAll());
    $allCats  = $toArray($categoryModel->getAll());
    $twig->addGlobal('navPages',     $allPages);
    $twig->addGlobal('navCats',      $allCats);
    $twig->addGlobal('sidebarCats',  $allCats);
    $twig->addGlobal('sidebarPosts', $toArray($postModel->getLatest(5)));
    $twig->addGlobal('sidebarPages', $allPages);
    $twig->addGlobal('footerCats',   $allCats);
    $twig->addGlobal('footerPages',  $allPages);

    // ─── Request globals ─────────────────────────────────────────────────────
    $twig->addGlobal('current_page', basename($_SERVER['PHP_SELF']));
    $twig->addGlobal('current_slug', $_GET['slug']      ?? '');
    $twig->addGlobal('current_cat',  $_GET['cat_post']  ?? '');
    $twig->addGlobal('search_query', Format::e($_GET['search'] ?? ''));

    // ─── Admin globals ───────────────────────────────────────────────────────
    $twig->addGlobal('admin_username', Session::get('userName'));
    $twig->addGlobal('admin_role',     Session::get('userRole'));

    // ─── Base URL (always points to the project web root) ───────────────────
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $basePath  = rtrim($scriptDir, '/') . '/';
    $twig->addGlobal('base_url', $basePath);
}
