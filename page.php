<?php
require_once __DIR__ . '/app/bootstrap.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header('Location: index.php');
    exit();
}

$pageSlug = $_GET['slug'];
$pageData = $pageModel->getBySlug($pageSlug);

if (!$pageData) {
    header('Location: 404.php');
    exit();
}
echo $twig->render('frontend/page.twig', [
    'pageData' => $pageData
]);