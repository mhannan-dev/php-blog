<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can add sliders
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
        $title = trim($_POST['title'] ?? '');

        // Image upload
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $file        = $_FILES['image'] ?? null;
        $fileName    = $file['name']     ?? '';
        $fileSize    = $file['size']     ?? 0;
        $fileTmp     = $file['tmp_name'] ?? '';
        $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($title === '' || $fileName === '') {
            $error = 'Fields must not be empty.';
        } elseif ($fileSize > 1_048_576) {
            $error = 'Image size must be less than 1 MB.';
        } elseif (!in_array($fileExt, $allowedExts, true)) {
            $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
        } else {
            $uniqueName   = bin2hex(random_bytes(8)) . '.' . $fileExt;
            $uploadedPath = 'upload/' . $uniqueName;

            if (!move_uploaded_file($fileTmp, $uploadedPath)) {
                $error = 'Failed to upload image.';
            } else {
                $inserted = $siteModel->createSlider($uploadedPath, $title);
                if ($inserted) {
                    header('Location: slider_list.php');
                    exit();
                } else {
                    $error = 'Slider Not Inserted!';
                }
            }
        }
    }
}
echo $twig->render('dashboard/add_slider.twig', [
    'error'      => $error,
    'success'    => $success,
    'csrfToken'  => Session::getCsrfToken(),
    'form_title' => $_POST['title'] ?? ''
]);