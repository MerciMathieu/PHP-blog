<?php 

namespace App\Controller;

use App\Classes\TwigLoader;

Class ContactController {

    public function index() {

        $twigLoader = new TwigLoader();
        $twig = $twigLoader->load();

        echo $twig->render('contact.html.twig', [
            'title' => 'contact'
        ]);
    }
}

