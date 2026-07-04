<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

$success = '';
$error   = '';

// Single delete action handler — only via GET param 'delpost'
if (isset($_GET['delpost']) && (int) $_GET['delpost'] > 0) {
    $delId = (int) $_GET['delpost'];
    
    $post = $postModel->getById($delId);
    if ($post) {
        $canDelete = ((int) Session::get('userId') === (int) $post['userid'] || Session::get('userRole') === '0');
        if (!$canDelete) {
            $error = 'You do not have permission to delete this post.';
        } else {
            $deleted = $postModel->delete($delId);
            if ($deleted) {
                $success = 'Post deleted successfully.';
            } else {
                $error = 'Failed to delete the post.';
            }
        }
    } else {
        $error = 'Post not found.';
    }
}
?>

$postsResult = $postModel->getAllWithCategory();
$postsArray = [];
if ($postsResult) {
    while ($post = $postsResult->fetch_assoc()) {
        $postsArray[] = $post;
    }
}

echo $twig->render('dashboard/post_list.twig', [
    'error'           => $error,
    'success'         => $success,
    'posts'           => $postsArray,
    'current_user_id' => Session::get('userId')
]);
