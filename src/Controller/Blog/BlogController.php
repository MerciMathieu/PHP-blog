<?php 

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;

Class BlogController
{
    /**
     * @var \Twig\Environment
     */
    private $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index() 
    {
        $postRepository = new PostRepository();
        $posts = $postRepository->findAll();

        return $this->twig->render('blog/blog.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function showPost() 
    {
        $postRepository = new PostRepository();
        $post = $postRepository->find(1);

        $commentRepository = new CommentRepository();
        $comments = $commentRepository->findAll();

        return $this->twig->render('blog/showpost.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }
}

