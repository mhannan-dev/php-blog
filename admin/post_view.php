<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

if (!isset($_GET['post_id']) || (int) $_GET['post_id'] <= 0) {
    header('Location: post_list.php');
    exit();
}

$id = (int) $_GET['post_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location: post_list.php');
    exit();
}

$post_result = $postModel->getById($id);
if (!$post_result) {
    header('Location: post_list.php');
    exit();
}
$catData = $categoryModel->getById((int) $post_result['cat']);
$catName = $catData ? $catData['name'] : 'None';

echo $twig->render('dashboard/post_view.twig', [
    'post_result' => $post_result,
    'cat_name'    => $catName
]);