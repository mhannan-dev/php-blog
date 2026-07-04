<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Twig\Environment;
use Post;
use Category;
use Session;
use Format;
use mysqli_result;

class PostController extends BaseController
{
    private Post $postModel;
    private Category $categoryModel;

    public function __construct(Environment $twig, Post $postModel, Category $categoryModel)
    {
        parent::__construct($twig);
        $this->postModel = $postModel;
        $this->categoryModel = $categoryModel;
    }

    public function list(): void
    {
        $this->requireLogin();

        $success = '';
        $error   = '';

        // Handle delete
        if (isset($_GET['delpost']) && (int) $_GET['delpost'] > 0) {
            $delId = (int) $_GET['delpost'];
            
            $post = $this->postModel->getById($delId);
            if ($post) {
                $canDelete = ((int) Session::get('userId') === (int) $post['userid'] || Session::get('userRole') === '0');
                if (!$canDelete) {
                    $error = 'You do not have permission to delete this post.';
                } else {
                    $deleted = $this->postModel->delete($delId);
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

        $postsResult = $this->postModel->getAllWithCategory();
        $postsArray = [];
        if ($postsResult && $postsResult instanceof mysqli_result) {
            $postsArray = $postsResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('dashboard/post_list.twig', [
            'error'           => $error,
            'success'         => $success,
            'posts'           => $postsArray,
            'current_user_id' => Session::get('userId')
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                        $slug     = Format::slugify($title);
                        $inserted = $this->postModel->create($title, $slug, $body, $cat, $author, $uploadedPath, $userId);

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

        $categoriesResult = $this->categoryModel->getAll();
        $categoriesArray = [];
        if ($categoriesResult && $categoriesResult instanceof mysqli_result) {
            $categoriesArray = $categoriesResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('dashboard/addpost.twig', [
            'error'      => $error,
            'success'    => $success,
            'csrfToken'  => Session::getCsrfToken(),
            'categories' => $categoriesArray,
            'post_data'  => $_POST
        ]);
    }

    public function edit(): void
    {
        $this->requireLogin();

        if (!isset($_GET['post_id']) || (int) $_GET['post_id'] <= 0) {
            header('Location: post_list.php');
            exit();
        }

        $postId = (int) $_GET['post_id'];
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                    'title'       => $title,
                    'slug'        => Format::slugify($title),
                    'body'        => $body,
                    'category_id' => $cat,
                    'author'      => $author,
                    'user_id'     => $userId
                ];

                if ($title === '' || $body === '' || $cat <= 0 || $author === '' || $tags === '') {
                    $error = 'All fields are required.';
                } else {
                    if (!empty($fileName)) {
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
                                $updated = $this->postModel->updateWithImage($postId, $data, $uploadedPath);
                                if ($updated) {
                                    header('Location: post_list.php');
                                    exit();
                                } else {
                                    $error = 'Failed to update the post. Please try again.';
                                }
                            }
                        }
                    } else {
                        $updated = $this->postModel->update($postId, $data);
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

        $postData = $this->postModel->getById($postId);
        if (!$postData) {
            header('Location: post_list.php');
            exit();
        }

        $categoriesResult = $this->categoryModel->getAll();
        $categoriesArray = [];
        if ($categoriesResult && $categoriesResult instanceof mysqli_result) {
            $categoriesArray = $categoriesResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('dashboard/edit_post.twig', [
            'error'      => $error,
            'success'    => $success,
            'csrfToken'  => Session::getCsrfToken(),
            'categories' => $categoriesArray,
            'postData'   => $postData
        ]);
    }

    public function view(): void
    {
        $this->requireLogin();

        if (!isset($_GET['post_id']) || (int) $_GET['post_id'] <= 0) {
            header('Location: post_list.php');
            exit();
        }

        $postId = (int) $_GET['post_id'];
        $postData = $this->postModel->getById($postId);

        if (!$postData) {
            header('Location: post_list.php');
            exit();
        }

        $this->render('dashboard/post_view.twig', [
            'post' => $postData
        ]);
    }
}
