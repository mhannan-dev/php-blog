<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Twig\Environment;
use Page;
use Session;
use Format;
use mysqli_result;

class PageController extends BaseController
{
    private Page $pageModel;

    public function __construct(Environment $twig, Page $pageModel)
    {
        parent::__construct($twig);
        $this->pageModel = $pageModel;
    }

    public function list(): void
    {
        $this->requireAdmin();

        $pagesResult = $this->pageModel->getAll();
        $pagesArray = [];
        if ($pagesResult && $pagesResult instanceof mysqli_result) {
            $pagesArray = $pagesResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('dashboard/page_list.twig', [
            'pages' => $pagesArray
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $name = trim($_POST['name'] ?? '');
                $body = trim($_POST['body'] ?? '');

                if ($name === '' || $body === '') {
                    $error = 'Fields must not be empty.';
                } else {
                    $slug     = Format::slugify($name);
                    $inserted = $this->pageModel->create($name, $slug, $body);
                    if ($inserted) {
                        header('Location: page_list.php');
                        exit();
                    } else {
                        $error = 'Page Not Inserted!';
                    }
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

        if (!isset($_GET['page_id']) || (int) $_GET['page_id'] <= 0) {
            header('Location: index.php');
            exit();
        }

        $pageId = (int) $_GET['page_id'];
        $error   = '';
        $success = '';

        if (isset($_GET['delete_page'])) {
            $delId = (int) $_GET['delete_page'];
            if ($delId === $pageId) {
                $deleted = $this->pageModel->delete($delId);
                if ($deleted) {
                    header('Location: page_list.php');
                    exit();
                } else {
                    $error = 'Failed to delete page.';
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $name = trim($_POST['name'] ?? '');
                $body = trim($_POST['body'] ?? '');

                if ($name === '' || $body === '') {
                    $error = 'Fields must not be empty.';
                } else {
                    $updated = $this->pageModel->update($pageId, $name, $body);
                    if ($updated) {
                        $success = 'Page Updated Successfully.';
                    } else {
                        $error = 'Page Not Updated!';
                    }
                }
            }
        }

        $pageData = $this->pageModel->getById($pageId);
        if (!$pageData) {
            header('Location: index.php');
            exit();
        }

        $this->render('dashboard/page.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'pageData'  => $pageData
        ]);
    }
}
