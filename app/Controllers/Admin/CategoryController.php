<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Twig\Environment;
use Category;
use Session;
use mysqli_result;

class CategoryController extends BaseController
{
    private Category $categoryModel;

    public function __construct(Environment $twig, Category $categoryModel)
    {
        parent::__construct($twig);
        $this->categoryModel = $categoryModel;
    }

    public function list(): void
    {
        $this->requireLogin();

        $error   = '';
        $success = '';

        if (isset($_GET['delcat'])) {
            $delId = (int) $_GET['delcat'];
            if ($delId > 0) {
                $deleted = $this->categoryModel->delete($delId);
                if ($deleted) {
                    $success = 'Category deleted successfully.';
                } else {
                    $error = 'Category not deleted successfully.';
                }
            }
        }

        $categoriesResult = $this->categoryModel->getAll();
        $categoriesArray = [];
        if ($categoriesResult && $categoriesResult instanceof mysqli_result) {
            $categoriesArray = $categoriesResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('dashboard/catlist.twig', [
            'error'      => $error,
            'success'    => $success,
            'categories' => $categoriesArray
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
                $name = trim($_POST['name'] ?? '');

                if ($name === '') {
                    $error = 'Field must not be empty.';
                } else {
                    $inserted = $this->categoryModel->create($name);
                    if ($inserted) {
                        $success = 'Category Inserted Successfully.';
                    } else {
                        $error = 'Category Not Inserted!';
                    }
                }
            }
        }

        $this->render('dashboard/addcat.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken()
        ]);
    }

    public function edit(): void
    {
        $this->requireLogin();

        if (!isset($_GET['cat_id']) || (int) $_GET['cat_id'] <= 0) {
            header('Location: catlist.php');
            exit();
        }

        $catId = (int) $_GET['cat_id'];
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $name = trim($_POST['name'] ?? '');

                if ($name === '') {
                    $error = 'Field must not be empty.';
                } else {
                    $updated = $this->categoryModel->update($catId, $name);
                    if ($updated) {
                        header('Location: catlist.php');
                        exit();
                    } else {
                        $error = 'Category Not Updated!';
                    }
                }
            }
        }

        $category = $this->categoryModel->getById($catId);
        if (!$category) {
            header('Location: catlist.php');
            exit();
        }

        $this->render('dashboard/editcat.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'category'  => $category
        ]);
    }
}
