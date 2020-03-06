<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\Blog\BlogController;
use App\Controller\Home\HomeController;
use App\Controller\Admin\AdminController;

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
    'cache' => '../var/cache/templates',
    'auto_reload' => true
]);

$homeController = new HomeController($twig); 
$blogController = new BlogController($twig);
$adminController = new AdminController($twig);

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case "home":
            echo $homeController->index();
        break;
        case "blog":
            echo $blogController->index();
        break;
        case "post":
            echo $blogController->showPost();
        break;
        case "admin":
            echo $adminController->index();
        break;
        case "admin/article/edit":
            echo $adminController->editArticle();
        break;
        case "admin/article/add":
            echo $adminController->addArticle();
        break;
        case "admin/article/comments":
            echo $adminController->showComments();
        break;
        default:
            echo $homeController->index();
        break;
    }
} else { 
    echo $homeController->index();
}