<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') may add users.
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role     = trim($_POST['role']     ?? '');

        if ($username === '' || $password === '' || $role === '') {
            $error = 'All fields are required.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif (!in_array($role, ['0', '1', '2'], true)) {
            $error = 'Invalid role selected.';
        } else {
            // Check for duplicate username using UserModel
            if ($userModel->usernameExists($username)) {
                $error = 'Username already exists. Please choose a different username.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $inserted       = $userModel->create($username, $hashedPassword, (int) $role);

                if ($inserted) {
                    $success  = 'User created successfully.';
                    $username = ''; // Clear form
                } else {
                    $error = 'Failed to create user. Please try again.';
                }
            }
        }
    }
}
?>

echo $twig->render('dashboard/add_user.twig', [
    'error'     => $error,
    'success'   => $success,
    'csrfToken' => Session::getCsrfToken(),
    'username'  => $username ?? ''
]);
