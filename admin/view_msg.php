<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can view inbox messages
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['msg_id']) || (int) $_GET['msg_id'] <= 0) {
    header('Location: inbox.php');
    exit();
}

$id = (int) $_GET['msg_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location: inbox.php');
    exit();
}

$result = $contactModel->getById($id);
if (!$result) {
    header('Location: inbox.php');
    exit();
}
echo $twig->render('dashboard/view_msg.twig', [
    'msg_result' => $result
]);