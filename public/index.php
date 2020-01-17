<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Classes\TwigLoader;
use App\Controller\HomeController;
use App\Controller\ContactController;

$loader = new \Twig\Loader\FilesystemLoader('../view');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
    'cache' => '../var/cache/templates',
    'auto_reload' => true
]);

$homeController = new HomeController($twig); 
$contactController = new ContactController($twig);

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case "home":
            return $homeController->index();
        break;
        case "contact":
            return $contactController->index();
        break;
        default:
            return $homeController->index();
    break;
    }
} else { 
    return $homeController->index();
}