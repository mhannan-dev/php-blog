<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $error = 'Field must not be empty.';
        } else {
            $inserted = $categoryModel->create($name);
            if ($inserted) {
                $success = 'Category inserted successfully.';
            } else {
                $error = 'Category not inserted successfully.';
            }
        }
    }
}
echo $twig->render('dashboard/addcat.twig', [
    'error'     => $error,
    'success'   => $success,
    'csrfToken' => Session::getCsrfToken()
]);
