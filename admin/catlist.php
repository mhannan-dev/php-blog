<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

$error   = '';
$success = '';

if (isset($_GET['delcat'])) {
    $delId = (int) $_GET['delcat'];
    if ($delId > 0) {
        $deleted = $categoryModel->delete($delId);
        if ($deleted) {
            $success = 'Category deleted successfully.';
        } else {
            $error = 'Category not deleted successfully.';
        }
    }
}
?>

$categoriesResult = $categoryModel->getAll();
$categoriesArray = [];
if ($categoriesResult && $categoriesResult->num_rows > 0) {
    while ($cat = $categoriesResult->fetch_assoc()) {
        $categoriesArray[] = $cat;
    }
}

echo $twig->render('dashboard/catlist.twig', [
    'error'      => $error,
    'success'    => $success,
    'categories' => $categoriesArray
]);
