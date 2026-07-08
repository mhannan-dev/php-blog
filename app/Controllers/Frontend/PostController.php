<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Contracts\PostRepositoryInterface;
use Twig\Environment;

class PostController extends BaseController
{
    private PostRepositoryInterface $postModel;

    public function __construct(Environment $twig, PostRepositoryInterface $postModel)
    {
        parent::__construct($twig);
        $this->postModel = $postModel;
    }

    public function show(): void
    {
        $postSlug = $this->getStringParam('slug');
        if ($postSlug === '') {
            $this->redirect('index.php');
        }

        $post = $this->postModel->getBySlug($postSlug);
        if (!$post) {
            $this->redirect('404.php');
        }

        $relatedPosts = $this->postModel->getRelated(
            (int) ($post['category_id'] ?? 0),
            (int) $post['id'],
            3
        );

        $this->render('frontend/post.twig', [
            'post'         => $post,
            'relatedPosts' => $relatedPosts
        ]);
    }
}
