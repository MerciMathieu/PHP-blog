<?php

namespace App\Classes;

require_once __DIR__ . '/../../vendor/autoload.php';

class TwigLoader {

    public function load() {
        $loader = new \Twig\Loader\FilesystemLoader('../view');
        $twig = new \Twig\Environment($loader, [
            'debug' => true,
            'cache' => '../var/cache/templates',
            'auto_reload' => true
        ]);

        return $twig;
    }

}