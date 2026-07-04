<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can add site title and slogan
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
        $title  = trim($_POST['title']  ?? '');
        $slogan = trim($_POST['slogan'] ?? '');

        // Image upload
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $file        = $_FILES['logo']  ?? null;
        $fileName    = $file['name']     ?? '';
        $fileSize    = $file['size']     ?? 0;
        $fileTmp     = $file['tmp_name'] ?? '';
        $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($title === '' || $slogan === '' || $fileName === '') {
            $error = 'All fields are required.';
        } elseif ($fileSize > 1_048_576) {
            $error = 'Logo size must be less than 1 MB.';
        } elseif (!in_array($fileExt, $allowedExts, true)) {
            $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
        } else {
            $uniqueName   = bin2hex(random_bytes(8)) . '.' . $fileExt;
            $uploadedPath = 'upload/' . $uniqueName;

            if (!move_uploaded_file($fileTmp, $uploadedPath)) {
                $error = 'Failed to upload logo.';
            } else {
                $inserted = $siteModel->createSiteInfo($uploadedPath, $title, $slogan);
                if ($inserted) {
                    header('Location: site_info_list.php');
                    exit();
                } else {
                    $error = 'Failed to save site information.';
                }
            }
        }
    }
}
echo $twig->render('dashboard/titleslogan.twig', [
    'error'       => $error,
    'success'     => $success,
    'csrfToken'   => Session::getCsrfToken(),
    'form_title'  => $_POST['title'] ?? '',
    'form_slogan' => $_POST['slogan'] ?? ''
]);
