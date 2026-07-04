<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Validate post ID from URL
if (!isset($_GET['post_id']) || (int) $_GET['post_id'] <= 0) {
    header('Location: post_list.php');
    exit();
}

$postId = (int) $_GET['post_id'];

$error   = '';
$success = '';

// Handle form submission
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

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $file        = $_FILES['image'] ?? null;
        $fileName    = $file['name']     ?? '';
        $fileSize    = $file['size']     ?? 0;
        $fileTmp     = $file['tmp_name'] ?? '';
        $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $data = [
            'title'  => $title,
            'body'   => $body,
            'cat'    => $cat,
            'author' => $author,
            'tags'   => $tags,
            'userid' => $userId
        ];

        if ($title === '' || $body === '' || $cat <= 0 || $author === '' || $tags === '') {
            $error = 'All fields are required.';
        } else {
            if (!empty($fileName)) {
                // A new image was provided — validate and replace.
                if ($fileSize > 1_048_576) {
                    $error = 'Image size must be less than 1 MB.';
                } elseif (!in_array($fileExt, $allowedExts, true)) {
                    $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
                } else {
                    $uniqueName   = bin2hex(random_bytes(8)) . '.' . $fileExt;
                    $uploadedPath = 'upload/' . $uniqueName;

                    if (!move_uploaded_file($fileTmp, $uploadedPath)) {
                        $error = 'Failed to upload image. Check folder permissions.';
                    } else {
                        $updated = $postModel->updateWithImage($postId, $data, $uploadedPath);
                        if ($updated) {
                            header('Location: post_list.php');
                            exit();
                        } else {
                            $error = 'Failed to update the post. Please try again.';
                        }
                    }
                }
            } else {
                // No new image — update everything else.
                $updated = $postModel->update($postId, $data);
                if ($updated) {
                    header('Location: post_list.php');
                    exit();
                } else {
                    $error = 'Failed to update the post. Please try again.';
                }
            }
        }
    }
}

// Load existing post data
$postData = $postModel->getById($postId);
if (!$postData) {
    header('Location: post_list.php');
    exit();
}
?>

$categoriesResult = $categoryModel->getAll();
$categoriesArray = [];
if ($categoriesResult) {
    while ($cat = $categoriesResult->fetch_assoc()) {
        $categoriesArray[] = $cat;
    }
}

echo $twig->render('dashboard/edit_post.twig', [
    'error'      => $error,
    'success'    => $success,
    'csrfToken'  => Session::getCsrfToken(),
    'categories' => $categoriesArray,
    'postData'   => $postData
]);