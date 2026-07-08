<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\PostRepositoryInterface;
use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\ContactRepositoryInterface;
use Twig\Environment;
use Session;

class DashboardController extends BaseController
{
    private PostRepositoryInterface $postModel;
    private CategoryRepositoryInterface $categoryModel;
    private ContactRepositoryInterface $contactModel;

    public function __construct(
        Environment $twig,
        PostRepositoryInterface $postModel,
        CategoryRepositoryInterface $categoryModel,
        ContactRepositoryInterface $contactModel
    ) {
        parent::__construct($twig);
        $this->postModel     = $postModel;
        $this->categoryModel = $categoryModel;
        $this->contactModel  = $contactModel;
    }

    public function index(): void
    {
        $this->requireLogin();

        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            Session::destroy();
        }

        $totalPosts = $this->postModel->getTotalCount();
        $unreadMsgs = $this->contactModel->getUnreadCount();
        $totalCats  = count($this->categoryModel->getAll());

        $this->render('dashboard/index.twig', [
            'totalPosts' => $totalPosts,
            'unreadMsgs' => $unreadMsgs,
            'totalCats'  => $totalCats
        ]);
    }
}
