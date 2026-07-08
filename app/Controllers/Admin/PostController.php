<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\PostRepositoryInterface;
use App\Contracts\CategoryRepositoryInterface;
use App\Security\InputValidator;
use App\Services\FileUploader;
use Session;
use Format;
use Twig\Environment;

class PostController extends BaseController
{
    private PostRepositoryInterface $postModel;
    private CategoryRepositoryInterface $categoryModel;
    private FileUploader $uploader;

    public function __construct(
        Environment $twig,
        PostRepositoryInterface $postModel,
        CategoryRepositoryInterface $categoryModel
    ) {
        parent::__construct($twig);
        $this->postModel     = $postModel;
        $this->categoryModel = $categoryModel;
        $this->uploader      = new FileUploader();
    }

    public function list(): void
    {
        $this->requireLogin();

        $success = '';
        $error   = '';

        $delId = $this->getIntParam('delpost');
        if ($delId > 0) {
            $post = $this->postModel->getById($delId);
            if ($post) {
                $canDelete = ((int) Session::get('userId') === (int) $post['userid'] || Session::get('userRole') === '0');
                if (!$canDelete) {
                    $error = 'You do not have permission to delete this post.';
                } else {
                    $deleted = $this->postModel->delete($delId);
                    $success = $deleted ? 'Post deleted successfully.' : 'Failed to delete the post.';
                    $error   = $deleted ? '' : 'Failed to delete the post.';
                }
            } else {
                $error = 'Post not found.';
            }
        }

        $posts = $this->postModel->getAllWithCategory();

        $this->render('dashboard/post_list.twig', [
            'error'           => $error,
            'success'         => $success,
            'posts'           => $posts,
            'current_user_id' => Session::get('userId')
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $categories = $this->categoryModel->getAll();
                $this->render('dashboard/addpost.twig', [
                    'error'      => $error,
                    'success'    => $success,
                    'csrfToken'  => Session::getCsrfToken(),
                    'categories' => $categories,
                    'post_data'  => $_POST
                ]);
                return;
            }

            $title  = $this->getRequestBody('title');
            $body   = $this->getRequestBody('body');
            $cat    = (int) ($_POST['cat'] ?? 0);
            $author = $this->getRequestBody('author');
            $tags   = $this->getRequestBody('tags');
            $userId = (int) Session::get('userId');

            $validator = new InputValidator();
            $validator
                ->required('title', $title, 'Title')
                ->required('body', $body, 'Body')
                ->required('author', $author, 'Author')
                ->required('tags', $tags, 'Tags')
                ->numeric('cat', $cat, 'Category');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $fileError = $this->uploader->validate($_FILES['image'] ?? []);
                if ($fileError !== null) {
                    $error = $fileError;
                } else {
                    $uploadResult = $this->uploader->upload($_FILES['image'] ?? []);
                    if (!$uploadResult['success']) {
                        $error = $uploadResult['error'];
                    } else {
                        $slug     = Format::slugify($title);
                        $inserted = $this->postModel->create([
                            'title'       => $title,
                            'slug'        => $slug,
                            'body'        => $body,
                            'category_id' => $cat,
                            'author'      => $author,
                            'image'       => $uploadResult['path'],
                            'user_id'     => $userId
                        ]);

                        if ($inserted) {
                            $this->redirect('post_list.php');
                        } else {
                            $error = 'Failed to save the post. Please try again.';
                        }
                    }
                }
            }
        }

        $categories = $this->categoryModel->getAll();

        $this->render('dashboard/addpost.twig', [
            'error'      => $error,
            'success'    => $success,
            'csrfToken'  => Session::getCsrfToken(),
            'categories' => $categories,
            'post_data'  => $_POST
        ]);
    }

    public function edit(): void
    {
        $this->requireLogin();

        $postId = $this->getIntParam('post_id');
        if ($postId <= 0) {
            $this->redirect('post_list.php');
        }

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $postData = $this->postModel->getById($postId);
                $categories = $this->categoryModel->getAll();
                $this->render('dashboard/edit_post.twig', [
                    'error'      => $error,
                    'success'    => $success,
                    'csrfToken'  => Session::getCsrfToken(),
                    'categories' => $categories,
                    'postData'   => $postData
                ]);
                return;
            }

            $title  = $this->getRequestBody('title');
            $body   = $this->getRequestBody('body');
            $cat    = (int) ($_POST['cat'] ?? 0);
            $author = $this->getRequestBody('author');
            $tags   = $this->getRequestBody('tags');
            $userId = (int) Session::get('userId');
            $slug   = Format::slugify($title);

            $data = [
                'title'       => $title,
                'slug'        => $slug,
                'body'        => $body,
                'category_id' => $cat,
                'author'      => $author,
                'user_id'     => $userId
            ];

            $validator = new InputValidator();
            $validator
                ->required('title', $title, 'Title')
                ->required('body', $body, 'Body')
                ->required('author', $author, 'Author')
                ->required('tags', $tags, 'Tags')
                ->numeric('cat', $cat, 'Category');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $file = $_FILES['image'] ?? null;
                if ($file && !empty($file['name'])) {
                    $fileError = $this->uploader->validate($file);
                    if ($fileError !== null) {
                        $error = $fileError;
                    } else {
                        $uploadResult = $this->uploader->upload($file);
                        if (!$uploadResult['success']) {
                            $error = $uploadResult['error'];
                        } else {
                            $updated = $this->postModel->updateWithImage($postId, $data, $uploadResult['path']);
                            if ($updated) {
                                $this->redirect('post_list.php');
                            } else {
                                $error = 'Failed to update the post. Please try again.';
                            }
                        }
                    }
                } else {
                    $updated = $this->postModel->update($postId, $data);
                    if ($updated) {
                        $this->redirect('post_list.php');
                    } else {
                        $error = 'Failed to update the post. Please try again.';
                    }
                }
            }
        }

        $postData = $this->postModel->getById($postId);
        if (!$postData) {
            $this->redirect('post_list.php');
        }

        $categories = $this->categoryModel->getAll();

        $this->render('dashboard/edit_post.twig', [
            'error'      => $error,
            'success'    => $success,
            'csrfToken'  => Session::getCsrfToken(),
            'categories' => $categories,
            'postData'   => $postData
        ]);
    }

    public function view(): void
    {
        $this->requireLogin();

        $postId = $this->getIntParam('post_id');
        if ($postId <= 0) {
            $this->redirect('post_list.php');
        }

        $postData = $this->postModel->getById($postId);
        if (!$postData) {
            $this->redirect('post_list.php');
        }

        $this->render('dashboard/post_view.twig', [
            'post' => $postData
        ]);
    }
}
