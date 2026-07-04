<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

$userId   = (int) Session::get('userId');
$error    = '';
$success  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $name     = trim($_POST['name']     ?? '');
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $details  = trim($_POST['details']  ?? '');

        $data = [
            'name'     => $name,
            'username' => $username,
            'email'    => $email,
            'details'  => $details
        ];

        if (empty($name) || empty($username) || empty($email)) {
            $error = 'Name, username, and email are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $updated = $userModel->update($userId, $data);

            if ($updated) {
                Session::set('userName', $username);
                $success = 'Profile updated successfully.';
            } else {
                $error = 'Failed to update profile. Please try again.';
            }
        }
    }
}

$user = $userModel->getById($userId);
if (!$user) {
    header('Location: index.php');
    exit();
}
echo $twig->render('dashboard/profile.twig', [
    'error'     => $error,
    'success'   => $success,
    'csrfToken' => Session::getCsrfToken(),
    'user'      => $user
]);