<?php
require_once __DIR__ . '/app/bootstrap.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header('Location: index.php');
    exit();
}

$postSlug = $_GET['slug'];
$post     = $postModel->getBySlug($postSlug);

if (!$post) {
    header('Location: 404.php');
    exit();
}

$relatedPostsArray = [];
if ($relatedPosts) {
    while ($relatedItem = $relatedPosts->fetch_assoc()) {
        $relatedPostsArray[] = $relatedItem;
    }
}

echo $twig->render('frontend/post.twig', [
    'post'         => $post,
    'relatedPosts' => $relatedPostsArray
]);