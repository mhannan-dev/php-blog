<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can edit social links
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['social_id']) || (int) $_GET['social_id'] <= 0) {
    header('Location: social_list.php');
    exit();
}

$social_id = (int) $_GET['social_id'];

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
            $updated = $siteModel->updateSocial($social_id, $fb, $tw, $ln);
            if ($updated) {
                header('Location: social_list.php');
                exit();
            } else {
                $error = 'Social links not updated.';
            }
        }
    }
}

$social_data = $siteModel->getSocialById($social_id);
if (!$social_data) {
    header('Location: social_list.php');
    exit();
}
echo $twig->render('dashboard/social_edit.twig', [
    'error'       => $error,
    'success'     => $success,
    'csrfToken'   => Session::getCsrfToken(),
    'social_data' => $social_data
]);
