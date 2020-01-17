<?php 

namespace App\Controller;

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
        echo $this->twig->render('homepage.html.twig', [
            'title' => 'home'
        ]);
    }
}