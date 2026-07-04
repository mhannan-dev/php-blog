<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

$totalPosts = $postModel->getTotalCount();
$unreadMsgs = $contactModel->getUnreadCount();
$cats       = $categoryModel->getAll();
$totalCats  = $cats ? $cats->num_rows : 0;
echo $twig->render('dashboard/index.twig', [
    'totalPosts' => $totalPosts,
    'unreadMsgs' => $unreadMsgs,
    'totalCats'  => $totalCats
]);