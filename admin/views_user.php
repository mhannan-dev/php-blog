<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

if (!isset($_GET['userId']) || (int) $_GET['userId'] <= 0) {
    header('Location: user_list.php');
    exit();
}

$userId = (int) $_GET['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location: user_list.php');
    exit();
}

$user_result = $userModel->getById($userId);
if (!$user_result) {
    header('Location: user_list.php');
    exit();
}
echo $twig->render('dashboard/views_user.twig', [
    'user_result' => $user_result
]);