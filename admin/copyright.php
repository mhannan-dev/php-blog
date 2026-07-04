<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can edit copyright
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
        $note = trim($_POST['note'] ?? '');

        if ($note === '') {
            $error = 'Field must not be empty.';
        } else {
            $updated = $siteModel->updateFooter($note);
            if ($updated) {
                header('Location: index.php');
                exit();
            } else {
                $error = 'Failed to update copyright note.';
            }
        }
    }
}

$result = $siteModel->getFooterNote();
echo $twig->render('dashboard/copyright.twig', [
    'error'         => $error,
    'success'       => $success,
    'csrfToken'     => Session::getCsrfToken(),
    'footer_result' => $result
]);
