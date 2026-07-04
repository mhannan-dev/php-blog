<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can add pages
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
        $name = trim($_POST['name'] ?? '');
        $body = trim($_POST['body'] ?? '');

        if ($name === '' || $body === '') {
            $error = 'Fields must not be empty.';
        } else {
            $inserted = $pageModel->create($name, $body);
            if ($inserted) {
                header('Location: index.php');
                exit();
            } else {
                $error = 'Page Not Inserted!';
            }
        }
    }
}
echo $twig->render('dashboard/add_new_page.twig', [
    'error'     => $error,
    'success'   => $success,
    'csrfToken' => Session::getCsrfToken(),
    'page_data' => $_POST
]);