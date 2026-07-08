<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Contracts\PostRepositoryInterface;
use App\Contracts\SiteRepositoryInterface;
use App\Services\Paginator;
use Twig\Environment;

class HomeController extends BaseController
{
    private PostRepositoryInterface $postModel;
    private SiteRepositoryInterface $siteModel;

    public function __construct(
        Environment $twig,
        PostRepositoryInterface $postModel,
        SiteRepositoryInterface $siteModel
    ) {
        parent::__construct($twig);
        $this->postModel = $postModel;
        $this->siteModel = $siteModel;
    }

    public function index(): void
    {
        $totalPosts = $this->postModel->getTotalCount();
        $paginator  = Paginator::fromRequest($totalPosts, 5);

        $posts   = $this->postModel->getPaginated($paginator->offset(), $paginator->limit());
        $sliders = $this->siteModel->getSliders(4);

        $this->render('frontend/index.twig', [
            'posts'       => $posts,
            'sliders'     => $sliders,
            'currentPage' => $paginator->currentPage(),
            'totalPages'  => $paginator->totalPages()
        ]);
    }
}
