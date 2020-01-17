<?php 

namespace App\Controller;

Class ContactController
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
        echo $this->twig->render('contact.html.twig', [
            'title' => 'contact'
        ]);
    }
}

