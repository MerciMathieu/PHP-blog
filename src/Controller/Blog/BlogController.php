<?php 

namespace App\Controller\Blog;

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
        return $this->twig->render('blog/showpost.html.twig', [
        
        ]);
    }
}

