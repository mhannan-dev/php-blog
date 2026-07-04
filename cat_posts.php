<?php
require_once __DIR__ . '/app/bootstrap.php';

$catParam = trim($_GET['cat_post'] ?? '');

if (empty($catParam)) {
    header('Location: index.php');
    exit();
}

$category = $categoryModel->getByParam($catParam);

if (!$category) {
    header('Location: 404.php');
    exit();
}

$catPosts = $postModel->getByCategory((int) $category['id']);
$catPostsArray = [];
if ($catPosts) {
    while ($postItem = $catPosts->fetch_assoc()) {
        $catPostsArray[] = $postItem;
    }
}

echo $twig->render('frontend/category.twig', [
    'category' => $category,
    'catPosts' => $catPostsArray
]);