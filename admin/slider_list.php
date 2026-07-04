<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can view slider list
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

$error   = '';
$success = '';

if (isset($_GET['delete_slider'])) {
    $delId = (int) $_GET['delete_slider'];
    if ($delId > 0) {
        $deleted = $siteModel->deleteSlider($delId);
        if ($deleted) {
            $success = 'Slider deleted successfully.';
        } else {
            $error = 'Failed to delete slider.';
        }
    }
}
?>

$slidersResult = $siteModel->getSliders(20);
$sliders = [];
if ($slidersResult && $slidersResult->num_rows > 0) {
    while ($slider = $slidersResult->fetch_assoc()) {
        $sliders[] = $slider;
    }
}

echo $twig->render('dashboard/slider_list.twig', [
    'error'   => $error,
    'success' => $success,
    'sliders' => $sliders
]);
