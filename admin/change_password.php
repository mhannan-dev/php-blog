<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

$userId  = (int) Session::get('userId');
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $oldPassword     = trim($_POST['old_password']     ?? '');
        $newPassword     = trim($_POST['new_password']     ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = 'All fields are required.';
        } elseif (strlen($newPassword) < 6) {
            $error = 'New password must be at least 6 characters.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'New password and confirmation do not match.';
        } else {
            $user = $userModel->getById($userId);

            if (!$user) {
                $error = 'User account not found.';
            } else {
                $currentHash = $user['password'];
                $oldPasswordValid = password_verify($oldPassword, $currentHash)
                                 || $currentHash === md5($oldPassword);

                if (!$oldPasswordValid) {
                    $error = 'Current password is incorrect.';
                } else {
                    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
                    $updated = $userModel->updatePassword($userId, $newHash);

                    if ($updated) {
                        $success = 'Password changed successfully.';
                    } else {
                        $error = 'Failed to update password. Please try again.';
                    }
                }
            }
        }
    }
}
echo $twig->render('dashboard/change_password.twig', [
    'error'     => $error,
    'success'   => $success,
    'csrfToken' => Session::getCsrfToken()
]);