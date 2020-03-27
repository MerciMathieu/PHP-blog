<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Classes\Database;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Controller\Blog\BlogController;
use App\Controller\Home\HomeController;
use App\Controller\Admin\AdminController;

/**** TWIG ****/
$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
    'auto_reload' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
/**** /TWIG ****/

/**** Database ****/
$db = Database::connect();
/*****************/

/**** Repositories ****/
$postRepository = new PostRepository($db);
$commentRepository = new CommentRepository($db);
/**** /Repositories ****/

/**** Controllers ****/
$homeController = new HomeController($twig); 
$blogController = new BlogController($twig, $postRepository, $commentRepository);
$adminController = new AdminController($twig, $postRepository, $commentRepository);
/**** /Controllers ****/

$homeController = new HomeController($twig); 
$blogController = new BlogController($twig, $postRepository, $commentRepository);
$adminController = new AdminController($twig, $postRepository, $commentRepository);

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'index') {
        echo $homeController->index();
    }
    elseif ($_GET['action'] === 'blog') {
        echo $blogController->index();
    }
    elseif ($_GET['action'] === 'showpost') {
        $id = $_GET['id'];
        echo $blogController->showPost($id);
    }
    elseif ($_GET['action'] === 'admin') {
        echo $adminController->index();
    }
    elseif ($_GET['action'] === 'editpost') {
        $id = $_GET['id'];
        if (isset($_POST['submit'])) {
            echo $adminController->editPost($id);
        }
        echo $adminController->editPostForm($id);
    }
    elseif ($_GET['action'] === 'moderate-post-comments') {
        $id = $_GET['id'];
        echo $adminController->showCommentsFromPost($id);
    }
    elseif ($_GET['action'] === 'addpost') {
        if (isset($_POST['submit'])) {
            echo $adminController->insertPost();
        }
        echo $adminController->addPostForm();
    }
    elseif ($_GET['action'] === 'deletepost') {
        $id = $_GET['id'];
        echo $adminController->deletePost($id);
    }
    else { 
        echo $homeController->index();
    }
}
else { 
    echo $homeController->index();
}