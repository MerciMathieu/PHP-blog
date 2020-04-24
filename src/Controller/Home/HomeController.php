<?php 

namespace App\Controller\Home;

use App\Controller\AbstractController;

Class HomeController extends AbstractController
{

    public function index()
    {
        $currentUser = '';
        if ($this->getCurrentUser()) 
        {
            $currentUser = $this->getCurrentUser();
        }
        return $this->twig->render('homepage/homepage.html.twig', [
            'current_user' => $currentUser
        ]);
    }
}