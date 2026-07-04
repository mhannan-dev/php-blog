<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use Twig\Environment;
use Category;
use Post;
use mysqli_result;

class CategoryController extends BaseController
{
    private Category $categoryModel;
    private Post $postModel;

    public function __construct(Environment $twig, Category $categoryModel, Post $postModel)
    {
        parent::__construct($twig);
        $this->categoryModel = $categoryModel;
        $this->postModel = $postModel;
    }

    public function index(): void
    {
        $catParam = trim($_GET['cat_post'] ?? '');

        if (empty($catParam)) {
            header('Location: index.php');
            exit();
        }

        $category = $this->categoryModel->getByParam($catParam);

        if (!$category) {
            header('Location: 404.php');
            exit();
        }

        $catPosts = $this->postModel->getByCategory((int) $category['id']);
        $catPostsArray = [];
        if ($catPosts && $catPosts instanceof mysqli_result) {
            $catPostsArray = $catPosts->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('frontend/category.twig', [
            'category' => $category,
            'catPosts' => $catPostsArray
        ]);
    }
}
