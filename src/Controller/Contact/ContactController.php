<?php 

namespace App\Controller\Contact;

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
        return $this->twig->render('contact/contact.html.twig', [
        
        ]);
    }
}

