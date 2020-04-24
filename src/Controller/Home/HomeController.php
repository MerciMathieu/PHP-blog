<?php 

namespace App\Controller\Home;

use App\Controller\AbstractController;

Class HomeController extends AbstractController
{

    public function index()
    {
        return $this->twig->render('homepage/homepage.html.twig');
    }
}