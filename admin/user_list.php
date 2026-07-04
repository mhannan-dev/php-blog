<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

$error   = '';
$success = '';

if (isset($_GET['delUser'])) {
    if (Session::get('userRole') !== '0') {
        $error = 'You do not have permission to delete users.';
    } else {
        $delId = (int) $_GET['delUser'];
        if ($delId > 0) {
            $deleted = $userModel->delete($delId);
            if ($deleted) {
                $success = 'User deleted successfully.';
            } else {
                $error = 'User not deleted.';
            }
        }
    }
}
?>

$usersResult = $userModel->getAll();
$usersArray = [];
if ($usersResult && $usersResult->num_rows > 0) {
    while ($user = $usersResult->fetch_assoc()) {
        $usersArray[] = $user;
    }
}

echo $twig->render('dashboard/user_list.twig', [
    'error'   => $error,
    'success' => $success,
    'users'   => $usersArray
]);
