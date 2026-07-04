<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use Twig\Environment;
use Post;
use Site;
use mysqli_result;

class HomeController extends BaseController
{
    private Post $postModel;
    private Site $siteModel;

    public function __construct(Environment $twig, Post $postModel, Site $siteModel)
    {
        parent::__construct($twig);
        $this->postModel = $postModel;
        $this->siteModel = $siteModel;
    }

    public function index(): void
    {
        $perPage     = 5;
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $offset      = ($currentPage - 1) * $perPage;
        
        $totalPosts  = $this->postModel->getTotalCount();
        $totalPages  = (int) ceil($totalPosts / $perPage);
        $posts       = $this->postModel->getPaginated($offset, $perPage);
        $sliders     = $this->siteModel->getSliders(4);

        $postsArray = [];
        if ($posts && $posts instanceof mysqli_result) {
            $postsArray = $posts->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $slidersArray = [];
        if ($sliders && $sliders instanceof mysqli_result) {
            $slidersArray = $sliders->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('frontend/index.twig', [
            'posts'       => $postsArray,
            'sliders'     => $slidersArray,
            'currentPage' => $currentPage,
            'totalPages'  => $totalPages
        ]);
    }
}
