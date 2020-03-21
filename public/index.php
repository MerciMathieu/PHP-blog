<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Classes\Database;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
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

$postRepository = new PostRepository($db);
$commentRepository = new CommentRepository($db);

$homeController = new HomeController($twig); 
$blogController = new BlogController($twig, $postRepository, $commentRepository);
$adminController = new AdminController($twig, $postRepository, $commentRepository);


/* A REFACTORISEEEEER */

/* if ($_GET['action'] === 'index') {
    echo $homeController->index();
}

if ($_GET['action'] === 'showpost') {
    $id = $_GET['id'];
    echo $blogController->showPost($id);
} */

if (isset($_GET['path'])) {
    switch ($_GET['path']) {
        case "home":
            echo $homeController->index();
        break;
        case "blog":
            echo $blogController->index();
        break;
        case "post":
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            }
            echo $blogController->showPost($id);
        break;
        case "admin":
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'edit':
                        if (isset($_GET['class']) && $_GET['class'] === 'post') {
                            if (isset($_GET['id'])) {
                                $id = $_GET['id'];
                                echo $adminController->editPostForm($id);
                            } else {
                                echo $adminController->index();
                            }
                        }
                        break;
                    case 'moderatecomments':
                        if (isset($_GET['class']) && $_GET['class'] === 'post') {
                            if (isset($_GET['id'])) {
                                $id = $_GET['id'];
                                echo $adminController->showCommentsFromPost($id);
                            } else {
                                echo $adminController->index();
                            }
                        } else {
                            var_dump("la classe n'existe pas");
                        }
                        break;
                    case 'add':
                        if (isset($_GET['class']) && $_GET['class'] === 'post') {
                            if (isset($_POST['submit'])) {
                                echo $adminController->insertPost();
                            }
                            echo $adminController->addPostForm();
                        } else {
                            var_dump("la classe n'existe pas");
                        }
                        break;
                    
                    default:
                        echo $adminController->index();
                        break;
                }
            } else {
                echo $adminController->index();
            }
        break;
        default:
            echo $homeController->index();
        break;
    }
} else { 
    echo $homeController->index();
}