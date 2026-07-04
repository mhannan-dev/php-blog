<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

if (!isset($_GET['cat_id']) || (int) $_GET['cat_id'] <= 0) {
    header('Location: catlist.php');
    exit();
}

$id = (int) $_GET['cat_id'];

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
            $updated = $categoryModel->update($id, $name);
            if ($updated) {
                header('Location: catlist.php');
                exit();
            } else {
                $error = 'Category not updated successfully.';
            }
        }
    }
}

$cat_result = $categoryModel->getById($id);
if (!$cat_result) {
    header('Location: catlist.php');
    exit();
}
echo $twig->render('dashboard/editcat.twig', [
    'error'      => $error,
    'success'    => $success,
    'csrfToken'  => Session::getCsrfToken(),
    'cat_result' => $cat_result
]);
