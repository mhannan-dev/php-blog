<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\PostRepositoryInterface;
use Twig\Environment;

class CategoryController extends BaseController
{
    private CategoryRepositoryInterface $categoryModel;
    private PostRepositoryInterface $postModel;

    public function __construct(
        Environment $twig,
        CategoryRepositoryInterface $categoryModel,
        PostRepositoryInterface $postModel
    ) {
        parent::__construct($twig);
        $this->categoryModel = $categoryModel;
        $this->postModel = $postModel;
    }

    public function index(): void
    {
        $catParam = $this->getStringParam('cat_post');
        if ($catParam === '') {
            $this->redirect('index.php');
        }

        $category = $this->categoryModel->getByParam($catParam);
        if (!$category) {
            $this->redirect('404.php');
        }

        $catPosts = $this->postModel->getByCategory((int) $category['id']);

        $this->render('frontend/category.twig', [
            'category' => $category,
            'catPosts' => $catPosts
        ]);
    }
}
