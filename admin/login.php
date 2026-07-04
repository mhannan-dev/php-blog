<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkLogin(); 
// Redirect logged-in users away from login page

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $error = 'Username and password are required.';
        } else {
            $user = $userModel->getByUsername($username);

            if ($user) {
                $passwordValid = false;

                if (password_verify($password, $user['password'])) {
                    $passwordValid = true;
                } elseif ($user['password'] === md5($password)) {
                    // Legacy MD5 match — rehash to bcrypt automatically
                    $newHash = password_hash($password, PASSWORD_BCRYPT);
                    $userModel->updatePassword((int) $user['id'], $newHash);
                    $passwordValid = true;
                }

                if ($passwordValid) {
                    Session::set('login',    true);
                    Session::set('userId',   $user['id']);
                    Session::set('userRole', $user['role']);
                    Session::set('userName', $user['username']);
                    header('Location: index.php');
                    exit();
                } else {
                    $error = 'Username or password is incorrect.';
                }
            } else {
                $error = 'Username or password is incorrect.';
            }
        }
    }
}
echo $twig->render('dashboard/login.twig', [
    'error'     => $error,
    'csrfToken' => Session::getCsrfToken()
]);