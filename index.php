<?php
require_once __DIR__ . '/app/bootstrap.php';

$perPage     = 5;
$currentPage = max(1, (int) ($_GET['page'] ?? 1));
$offset      = ($currentPage - 1) * $perPage;
$totalPosts  = $postModel->getTotalCount();
$totalPages  = (int) ceil($totalPosts / $perPage);
$posts       = $postModel->getPaginated($offset, $perPage);

// Pass data to Twig
$sliders = $siteModel->getSliders(4);

// Convert MySQLi results to arrays for Twig, or rely on Twig's ability to iterate over Traversable
$postsArray = [];
if ($posts) {
    while ($postItem = $posts->fetch_assoc()) {
        $postsArray[] = $postItem;
    }
}

$slidersArray = [];
if ($sliders) {
    while ($slideItem = $sliders->fetch_assoc()) {
        $slidersArray[] = $slideItem;
    }
}

echo $twig->render('frontend/index.twig', [
    'posts'       => $postsArray,
    'sliders'     => $slidersArray,
    'currentPage' => $currentPage,
    'totalPages'  => $totalPages
]);
?>