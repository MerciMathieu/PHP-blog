<?php 

namespace App\Controller\Home;

Class HomeController
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
        return $this->twig->render('homepage/homepage.html.twig', [
            
        ]);
    }
}