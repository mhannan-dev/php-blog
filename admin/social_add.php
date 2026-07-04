<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can add social links
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
        $fb = trim($_POST['fb'] ?? '');
        $tw = trim($_POST['tw'] ?? '');
        $ln = trim($_POST['ln'] ?? '');

        if ($fb === '' || $tw === '' || $ln === '') {
            $error = 'All fields must not be empty.';
        } else {
            $inserted = $siteModel->createSocial($fb, $tw, $ln);
            if ($inserted) {
                $success = 'Social links inserted successfully.';
            } else {
                $error = 'Social links not inserted successfully.';
            }
        }
    }
}
echo $twig->render('dashboard/social_add.twig', [
    'error'     => $error,
    'success'   => $success,
    'csrfToken' => Session::getCsrfToken()
]);
