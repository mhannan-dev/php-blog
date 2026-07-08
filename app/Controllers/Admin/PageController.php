<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\PageRepositoryInterface;
use App\Security\InputValidator;
use Session;
use Format;
use Twig\Environment;

class PageController extends BaseController
{
    private PageRepositoryInterface $pageModel;

    public function __construct(Environment $twig, PageRepositoryInterface $pageModel)
    {
        parent::__construct($twig);
        $this->pageModel = $pageModel;
    }

    public function list(): void
    {
        $this->requireAdmin();

        $this->render('dashboard/page_list.twig', [
            'pages' => $this->pageModel->getAll()
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $this->render('dashboard/add_new_page.twig', [
                    'error'     => $error,
                    'success'   => $success,
                    'csrfToken' => Session::getCsrfToken(),
                    'page_data' => $_POST
                ]);
                return;
            }

            $name = $this->getRequestBody('name');
            $body = $this->getRequestBody('body');

            $validator = new InputValidator();
            $validator
                ->required('name', $name, 'Page name')
                ->required('body', $body, 'Body');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $slug     = Format::slugify($name);
                $inserted = $this->pageModel->create([
                    'name' => $name,
                    'slug' => $slug,
                    'body' => $body
                ]);
                if ($inserted) {
                    $this->redirect('page_list.php');
                } else {
                    $error = 'Page Not Inserted!';
                }
            }
        }

        $this->render('dashboard/add_new_page.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'page_data' => $_POST
        ]);
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $pageId = $this->getIntParam('page_id');
        if ($pageId <= 0) {
            $this->redirect('index.php');
        }

        $error   = '';
        $success = '';

        $delId = $this->getIntParam('delete_page');
        if ($delId === $pageId) {
            $deleted = $this->pageModel->delete($delId);
            if ($deleted) {
                $this->redirect('page_list.php');
            } else {
                $error = 'Failed to delete page.';
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $pageData = $this->pageModel->getById($pageId);
                $this->render('dashboard/page.twig', [
                    'error'     => $error,
                    'success'   => $success,
                    'csrfToken' => Session::getCsrfToken(),
                    'pageData'  => $pageData
                ]);
                return;
            }

            $name = $this->getRequestBody('name');
            $body = $this->getRequestBody('body');

            $validator = new InputValidator();
            $validator
                ->required('name', $name, 'Page name')
                ->required('body', $body, 'Body');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $slug    = Format::slugify($name);
                $updated = $this->pageModel->update($pageId, [
                    'name' => $name,
                    'slug' => $slug,
                    'body' => $body
                ]);
                if ($updated) {
                    $success = 'Page Updated Successfully.';
                } else {
                    $error = 'Page Not Updated!';
                }
            }
        }

        $pageData = $this->pageModel->getById($pageId);
        if (!$pageData) {
            $this->redirect('index.php');
        }

        $this->render('dashboard/page.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'pageData'  => $pageData
        ]);
    }
}
