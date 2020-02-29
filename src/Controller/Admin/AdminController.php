<?php 

namespace App\Controller\Admin;

Class AdminController
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
        return $this->twig->render('admin/admin.html.twig', [
        
        ]);
    }

    public function edit() 
    {
        return $this->twig->render('admin/edit.html.twig', [
        
        ]);
    }
}

