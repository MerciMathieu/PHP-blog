<?php 

namespace App\Controller;

use App\Classes\TwigLoader;

Class HomeController {
    
    public function index() {

        $twigLoader = new TwigLoader();
        $twig = $twigLoader->load();

        echo $twig->render('homepage.html.twig', [
            'title' => 'home'
        ]);
    }
}