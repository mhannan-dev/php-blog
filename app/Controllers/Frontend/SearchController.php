<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Contracts\PostRepositoryInterface;
use App\Security\InputValidator;
use Twig\Environment;

class SearchController extends BaseController
{
    private PostRepositoryInterface $postModel;

    public function __construct(Environment $twig, PostRepositoryInterface $postModel)
    {
        parent::__construct($twig);
        $this->postModel = $postModel;
    }

    public function index(): void
    {
        $searchTerm   = $this->getStringParam('search');
        $searchResult = $searchTerm !== '' ? $this->postModel->search($searchTerm) : [];

        $this->render('frontend/search.twig', [
            'searchTerm'   => InputValidator::sanitize($searchTerm),
            'searchResult' => $searchResult
        ]);
    }
}
