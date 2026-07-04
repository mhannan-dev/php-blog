<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use Twig\Environment;
use Page;

class PageController extends BaseController
{
    private Page $pageModel;

    public function __construct(Environment $twig, Page $pageModel)
    {
        parent::__construct($twig);
        $this->pageModel = $pageModel;
    }

    public function show(): void
    {
        if (!isset($_GET['slug']) || empty($_GET['slug'])) {
            header('Location: index.php');
            exit();
        }

        $pageSlug = $_GET['slug'];
        $pageData = $this->pageModel->getBySlug($pageSlug);

        if (!$pageData) {
            header('Location: 404.php');
            exit();
        }
        
        $this->render('frontend/page.twig', [
            'pageData' => $pageData
        ]);
    }
}
