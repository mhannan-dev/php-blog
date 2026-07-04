<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $title  = trim($_POST['title']  ?? '');
        $body   = trim($_POST['body']   ?? '');
        $cat    = (int) ($_POST['cat']    ?? 0);
        $author = trim($_POST['author'] ?? '');
        $tags   = trim($_POST['tags']   ?? '');
        $userId = (int) Session::get('userId');

        // Image upload
        $allowedExts  = ['jpg', 'jpeg', 'png', 'gif'];
        $file         = $_FILES['image'] ?? null;
        $fileName     = $file['name']     ?? '';
        $fileSize     = $file['size']     ?? 0;
        $fileTmp      = $file['tmp_name'] ?? '';
        $fileExt      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($title === '' || $body === '' || $cat <= 0 || $author === '' || $fileName === '' || $tags === '') {
            $error = 'All fields are required.';
        } elseif ($fileSize > 1_048_576) {
            $error = 'Image size must be less than 1 MB.';
        } elseif (!in_array($fileExt, $allowedExts, true)) {
            $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
        } else {
            $uniqueName   = bin2hex(random_bytes(8)) . '.' . $fileExt;
            $uploadedPath = 'upload/' . $uniqueName;

            if (!move_uploaded_file($fileTmp, $uploadedPath)) {
                $error = 'Failed to upload image. Check folder permissions.';
            } else {
                $inserted = $postModel->create($title, $body, $cat, $author, $uploadedPath, $tags, $userId);

                if ($inserted) {
                    header('Location: post_list.php');
                    exit();
                } else {
                    $error = 'Failed to save the post. Please try again.';
                }
            }
        }
    }
}
?>

$categoriesResult = $categoryModel->getAll();
$categoriesArray = [];
if ($categoriesResult) {
    while ($cat = $categoriesResult->fetch_assoc()) {
        $categoriesArray[] = $cat;
    }
}

echo $twig->render('dashboard/addpost.twig', [
    'error'      => $error,
    'success'    => $success,
    'csrfToken'  => Session::getCsrfToken(),
    'categories' => $categoriesArray,
    'post_data'  => $_POST
]);