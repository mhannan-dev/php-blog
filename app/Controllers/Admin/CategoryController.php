<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\CategoryRepositoryInterface;
use App\Security\InputValidator;
use Session;
use Twig\Environment;

class CategoryController extends BaseController
{
    private CategoryRepositoryInterface $categoryModel;

    public function __construct(Environment $twig, CategoryRepositoryInterface $categoryModel)
    {
        parent::__construct($twig);
        $this->categoryModel = $categoryModel;
    }

    public function list(): void
    {
        $this->requireLogin();

        $error   = '';
        $success = '';

        $delId = $this->getIntParam('delcat');
        if ($delId > 0) {
            $deleted = $this->categoryModel->delete($delId);
            if ($deleted) {
                $success = 'Category deleted successfully.';
            } else {
                $error = 'Category not deleted successfully.';
            }
        }

        $categories = $this->categoryModel->getAll();

        $this->render('dashboard/catlist.twig', [
            'error'      => $error,
            'success'    => $success,
            'categories' => $categories
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $this->render('dashboard/addcat.twig', [
                    'error'     => $error,
                    'success'   => $success,
                    'csrfToken' => Session::getCsrfToken()
                ]);
                return;
            }

            $name = $this->getRequestBody('name');

            $validator = new InputValidator();
            $validator->required('name', $name, 'Category name');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $inserted = $this->categoryModel->create($name);
                if ($inserted) {
                    $success = 'Category Inserted Successfully.';
                } else {
                    $error = 'Category Not Inserted!';
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

        $catId = $this->getIntParam('cat_id');
        if ($catId <= 0) {
            $this->redirect('catlist.php');
        }

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $category = $this->categoryModel->getById($catId);
                $this->render('dashboard/editcat.twig', [
                    'error'     => $error,
                    'success'   => $success,
                    'csrfToken' => Session::getCsrfToken(),
                    'category'  => $category
                ]);
                return;
            }

            $name = $this->getRequestBody('name');

            $validator = new InputValidator();
            $validator->required('name', $name, 'Category name');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $updated = $this->categoryModel->update($catId, $name);
                if ($updated) {
                    $this->redirect('catlist.php');
                } else {
                    $error = 'Category Not Updated!';
                }
            }
        }

        $category = $this->categoryModel->getById($catId);
        if (!$category) {
            $this->redirect('catlist.php');
        }

        $this->render('dashboard/editcat.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'category'  => $category
        ]);
    }
}
