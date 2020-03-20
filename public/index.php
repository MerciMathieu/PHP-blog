<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Classes\Database;
use App\Controller\Blog\BlogController;
use App\Controller\Home\HomeController;
use App\Controller\Admin\AdminController;

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
    'auto_reload' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$db = Database::connect();

$homeController = new HomeController($twig, $db); 
$blogController = new BlogController($twig, $db);
$adminController = new AdminController($twig, $db);


if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case "home":
            echo $homeController->index();
        break;
        case "blog":
            echo $blogController->index();
        break;
        case "post":
            if ($_GET['id']) {
                $id = $_GET['id'];
            }
            echo $blogController->showPost($id);
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