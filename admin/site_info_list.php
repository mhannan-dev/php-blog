<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can view site info list
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

$error   = '';
$success = '';

if (isset($_GET['site_info_id'])) {
    $delId = (int) $_GET['site_info_id'];
    if ($delId > 0) {
        $deleted = $siteModel->deleteSiteInfo($delId);
        if ($deleted) {
            $success = 'Information deleted successfully.';
        } else {
            $error = 'Failed to delete information.';
        }
    }
}
?>

$infosResult = $siteModel->getAllSiteInfo();
$infos = [];
if ($infosResult && $infosResult->num_rows > 0) {
    while ($info = $infosResult->fetch_assoc()) {
        $infos[] = $info;
    }
}

echo $twig->render('dashboard/site_info_list.twig', [
    'error'   => $error,
    'success' => $success,
    'infos'   => $infos
]);
