<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Category;
use Contact;
use Post;
use Session;
use Twig\Environment;

class DashboardController extends BaseController
{
    private Post $postModel;
    private Category $categoryModel;
    private Contact $contactModel;

    public function __construct(Environment $twig, Post $postModel, Category $categoryModel, Contact $contactModel)
    {
        parent::__construct($twig);
        $this->postModel     = $postModel;
        $this->categoryModel = $categoryModel;
        $this->contactModel  = $contactModel;
    }

    public function index(): void
    {
        $this->requireLogin();

        // Handle logout
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            Session::destroy();
            exit(); 
            // Session::destroy() redirects, but just in case
        }

        $totalPosts = $this->postModel->getTotalCount();
        $unreadMsgs = $this->contactModel->getUnreadCount();
        
        $cats       = $this->categoryModel->getAll();
        $totalCats  = $cats ? $cats->num_rows : 0;
        
        $this->render('dashboard/index.twig', [
            'totalPosts' => $totalPosts,
            'unreadMsgs' => $unreadMsgs,
            'totalCats'  => $totalCats
        ]);
    }
}
