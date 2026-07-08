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

// ─── Service Container ─────────────────────────────────────────────────────
$container = new \App\Core\Container();

// Register contracts → concrete implementations
$container->singleton(\App\Contracts\PostRepositoryInterface::class, function () use ($db) {
    return new Post($db);
});
$container->singleton(\App\Contracts\CategoryRepositoryInterface::class, function () use ($db) {
    return new Category($db);
});
$container->singleton(\App\Contracts\PageRepositoryInterface::class, function () use ($db) {
    return new Page($db);
});
$container->singleton(\App\Contracts\UserRepositoryInterface::class, function () use ($db) {
    return new User($db);
});
$container->singleton(\App\Contracts\ContactRepositoryInterface::class, function () use ($db) {
    return new Contact($db);
});
$container->singleton(\App\Contracts\SiteRepositoryInterface::class, function () use ($db) {
    return new Site($db);
});

// ─── Model Instances (backward-compatible aliases) ─────────────────────────
$post     = $postModel     = $container->get(\App\Contracts\PostRepositoryInterface::class);
$category = $categoryModel = $container->get(\App\Contracts\CategoryRepositoryInterface::class);
$page     = $pageModel     = $container->get(\App\Contracts\PageRepositoryInterface::class);
$user     = $userModel     = $container->get(\App\Contracts\UserRepositoryInterface::class);
$contact  = $contactModel  = $container->get(\App\Contracts\ContactRepositoryInterface::class);
$site     = $siteModel     = $container->get(\App\Contracts\SiteRepositoryInterface::class);

// ─── Twig Setup ────────────────────────────────────────────────────────────
$twig = null;
if (class_exists('Twig\Environment')) {
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/views');
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
        'debug' => ($_ENV['APP_ENV'] ?? 'production') === 'development',
    ]);

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
    $twig->addGlobal('navPages',     $pageModel->getAll());
    $twig->addGlobal('navCats',      $categoryModel->getAll());
    $twig->addGlobal('sidebarCats',  $categoryModel->getAll());
    $twig->addGlobal('sidebarPosts', $postModel->getLatest(5));
    $twig->addGlobal('sidebarPages', $pageModel->getAll());
    $twig->addGlobal('footerCats',   $categoryModel->getAll());
    $twig->addGlobal('footerPages',  $pageModel->getAll());

    // ─── Request globals ─────────────────────────────────────────────────────
    $twig->addGlobal('current_page', basename($_SERVER['PHP_SELF']));
    $twig->addGlobal('current_slug', $_GET['slug']      ?? '');
    $twig->addGlobal('current_cat',  $_GET['cat_post']  ?? '');
    $twig->addGlobal('search_query', Format::e($_GET['search'] ?? ''));

    // ─── Admin globals ───────────────────────────────────────────────────────
    $twig->addGlobal('admin_username', Session::get('userName'));
    $twig->addGlobal('admin_role',     Session::get('userRole'));
    $twig->addGlobal('csrf_token',     Session::getCsrfToken());

    // ─── Base URL (always points to the project web root) ───────────────────
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $basePath  = rtrim($scriptDir, '/') . '/';
    $twig->addGlobal('base_url', $basePath);
}
