<?php
require_once __DIR__ . '/app/bootstrap.php';

$searchTerm   = trim($_GET['search'] ?? '');
$searchResult = $searchTerm !== '' ? $postModel->search($searchTerm) : false;
?>

$searchResultArray = [];
if ($searchResult) {
    while ($postItem = $searchResult->fetch_assoc()) {
        $searchResultArray[] = $postItem;
    }
}

echo $twig->render('frontend/search.twig', [
    'searchTerm'   => $searchTerm,
    'searchResult' => $searchResultArray
]);