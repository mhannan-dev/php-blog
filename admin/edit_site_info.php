<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can edit site info
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['info_id']) || (int) $_GET['info_id'] <= 0) {
    header('Location: site_info_list.php');
    exit();
}

$id = (int) $_GET['info_id'];

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

        if ($title === '' || $slogan === '') {
            $error = 'Title and slogan must not be empty.';
        } else {
            if (!empty($fileName)) {
                if ($fileSize > 1_048_576) {
                    $error = 'Logo size must be less than 1 MB.';
                } elseif (!in_array($fileExt, $allowedExts, true)) {
                    $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
                } else {
                    $uniqueName   = bin2hex(random_bytes(8)) . '.' . $fileExt;
                    $uploadedPath = 'upload/' . $uniqueName;

                    if (!move_uploaded_file($fileTmp, $uploadedPath)) {
                        $error = 'Failed to upload logo.';
                    } else {
                        $updated = $siteModel->updateSiteInfo($id, $title, $slogan, $uploadedPath);
                        if ($updated) {
                            header('Location: site_info_list.php');
                            exit();
                        } else {
                            $error = 'Failed to update site info.';
                        }
                    }
                }
            } else {
                $updated = $siteModel->updateSiteInfo($id, $title, $slogan);
                if ($updated) {
                    header('Location: site_info_list.php');
                    exit();
                } else {
                    $error = 'Failed to update site info.';
                }
            }
        }
    }
}

$post_result = $siteModel->getSiteInfoById($id);
if (!$post_result) {
    header('Location: site_info_list.php');
    exit();
}
echo $twig->render('dashboard/edit_site_info.twig', [
    'error'       => $error,
    'csrfToken'   => Session::getCsrfToken(),
    'post_result' => $post_result
]);