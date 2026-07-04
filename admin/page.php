<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can view or edit pages
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['page_id']) || (int) $_GET['page_id'] <= 0) {
    header('Location: index.php');
    exit();
}

$id = (int) $_GET['page_id'];

$error   = '';
$success = '';

// Handle page deletion
if (isset($_GET['del_page'])) {
    $delpage = (int) $_GET['del_page'];
    if ($delpage > 0) {
        $dlt_page = $pageModel->delete($delpage);
        if ($dlt_page) {
            header('Location: index.php');
            exit();
        } else {
            $error = 'Page not deleted successfully.';
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $body = trim($_POST['body'] ?? '');

        if ($name === '' || $body === '') {
            $error = 'Field must not be empty.';
        } else {
            $updated_rows = $pageModel->update($id, $name, $body);
            if ($updated_rows) {
                header('Location: index.php');
                exit();
            } else {
                $error = 'Page Not Updated!';
            }
        }
    }
}

$result = $pageModel->getById($id);
if (!$result) {
    header('Location: index.php');
    exit();
}
echo $twig->render('dashboard/page.twig', [
    'error'       => $error,
    'success'     => $success,
    'csrfToken'   => Session::getCsrfToken(),
    'page_result' => $result
]);