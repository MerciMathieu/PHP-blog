<?php 

namespace App\Controller;

use App\Classes\TwigLoader;

Class ContactController {

    public function index() {

        $twigLoader = new TwigLoader();
        $twig = $twigLoader->load();

        echo $twig->render('contact/contact.html.twig', [
            'title' => 'Contact'
        ]);
    }
}

