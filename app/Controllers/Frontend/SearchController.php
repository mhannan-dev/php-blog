<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use Twig\Environment;
use Post;
use mysqli_result;

class SearchController extends BaseController
{
    private Post $postModel;

    public function __construct(Environment $twig, Post $postModel)
    {
        parent::__construct($twig);
        $this->postModel = $postModel;
    }

    public function index(): void
    {
        $searchTerm   = trim($_GET['search'] ?? '');
        $searchResult = $searchTerm !== '' ? $this->postModel->search($searchTerm) : false;

        $searchResultArray = [];
        if ($searchResult && $searchResult instanceof mysqli_result) {
            $searchResultArray = $searchResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('frontend/search.twig', [
            'searchTerm'   => $searchTerm,
            'searchResult' => $searchResultArray
        ]);
    }
}
