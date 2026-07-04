<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can view page list
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}
?>

$pagesResult = $pageModel->getAll();
$pagesArray = [];
if ($pagesResult && $pagesResult->num_rows > 0) {
    while ($page = $pagesResult->fetch_assoc()) {
        $pagesArray[] = $page;
    }
}

echo $twig->render('dashboard/page_list.twig', [
    'pages' => $pagesArray
]);
