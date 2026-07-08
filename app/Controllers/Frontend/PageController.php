<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Contracts\PageRepositoryInterface;
use Twig\Environment;

class PageController extends BaseController
{
    private PageRepositoryInterface $pageModel;

    public function __construct(Environment $twig, PageRepositoryInterface $pageModel)
    {
        parent::__construct($twig);
        $this->pageModel = $pageModel;
    }

    public function show(): void
    {
        $pageSlug = $this->getStringParam('slug');
        if ($pageSlug === '') {
            $this->redirect('index.php');
        }

        $pageData = $this->pageModel->getBySlug($pageSlug);
        if (!$pageData) {
            $this->redirect('404.php');
        }

        $this->render('frontend/page.twig', [
            'pageData' => $pageData
        ]);
    }
}
