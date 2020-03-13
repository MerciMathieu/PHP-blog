<?php 

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Repository\PostRepository;

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
        return $this->twig->render('blog/blog.html.twig', [
        
        ]);
    }

    public function show() 
    {

        $postRepository = new PostRepository();
        $post = $postRepository->find(1);

        return $this->twig->render('blog/showpost.html.twig', [
            'post' => $post,
        ]);
    }
}

