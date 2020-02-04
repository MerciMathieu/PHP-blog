<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\Home\HomeController;
use App\Controller\Blog\BlogController;

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
    'cache' => '../var/cache/templates',
    'auto_reload' => true
]);

$homeController = new HomeController($twig); 
$blogController = new BlogController($twig);

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case "home":
            echo $homeController->index();
        break;
        case "blog":
            echo $blogController->index();
        break;
        default:
            echo $homeController->index();
    break;
    }
} else { 
    echo $homeController->index();
}