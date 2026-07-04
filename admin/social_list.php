<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can manage social links
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

$error   = '';
$success = '';

if (isset($_GET['del_social'])) {
    $delId = (int) $_GET['del_social'];
    if ($delId > 0) {
        $deleted = $siteModel->deleteSocial($delId);
        if ($deleted) {
            $success = 'Social entry deleted successfully.';
        } else {
            $error = 'Failed to delete social entry.';
        }
    }
}
?>

$socialsResult = $siteModel->getAllSocial();
$socials = [];
if ($socialsResult && $socialsResult->num_rows > 0) {
    while ($social = $socialsResult->fetch_assoc()) {
        $socials[] = $social;
    }
}

echo $twig->render('dashboard/social_list.twig', [
    'error'   => $error,
    'success' => $success,
    'socials' => $socials
]);
