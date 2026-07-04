<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use Twig\Environment;
use Post;
use mysqli_result;

class PostController extends BaseController
{
    private Post $postModel;

    public function __construct(Environment $twig, Post $postModel)
    {
        parent::__construct($twig);
        $this->postModel = $postModel;
    }

    public function show(): void
    {
        if (!isset($_GET['slug']) || empty($_GET['slug'])) {
            header('Location: index.php');
            exit();
        }

        $postSlug = $_GET['slug'];
        $post     = $this->postModel->getBySlug($postSlug);

        if (!$post) {
            header('Location: 404.php');
            exit();
        }

        // Fetch related posts from the same category
        $relatedPostsResult = $this->postModel->getRelated((int) ($post['category_id'] ?? 0), (int) $post['id'], 3);
        $relatedPostsArray  = [];
        if ($relatedPostsResult && $relatedPostsResult instanceof mysqli_result) {
            $relatedPostsArray = $relatedPostsResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('frontend/post.twig', [
            'post'         => $post,
            'relatedPosts' => $relatedPostsArray
        ]);
    }
}
