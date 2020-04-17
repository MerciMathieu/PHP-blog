<?php

require_once __DIR__ . '/../vendor/autoload.php';


use App\Classes\Database;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Controller\Blog\BlogController;
use App\Controller\Home\HomeController;
use App\Controller\Admin\AdminController;
use App\Controller\Login\LoginController;

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
$userRepository = new UserRepository($db);
/**** /Repositories ****/

/**** Controllers ****/
$homeController = new HomeController($twig); 
$blogController = new BlogController($twig, $postRepository, $commentRepository, $userRepository);
$adminController = new AdminController($twig, $postRepository, $commentRepository, $userRepository);
/**** /Controllers ****/

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'index') {
        echo $homeController->index();
    }
    elseif ($_GET['action'] === 'blog') {
        echo $blogController->index();
    }
    elseif ($_GET['action'] === 'showpost') {
        $postId = $_GET['postid'];
        echo $blogController->showPost($postId);
    }
    elseif ($_GET['action'] === 'login') {
        echo $blogController->login();
    }
    elseif ($_GET['action'] === 'register') {
        echo $blogController->register();
    }
    elseif ($_GET['action'] === 'admin') {
        echo $adminController->index();
    }
    elseif ($_GET['action'] === 'editpost') {
        $postId = $_GET['postid'];
        echo $adminController->editPost($postId);
    }
    elseif ($_GET['action'] === 'moderate-post-comments') {
        $postId = $_GET['postid'];
        echo $adminController->showCommentsFromPost($postId);
    }
    elseif ($_GET['action'] === 'addpost') {
        echo $adminController->addPost();
    }
    elseif ($_GET['action'] === 'deletepost') {
        $postId = $_GET['postid'];
        echo $adminController->deletePost($postId);
    }
    elseif ($_GET['action'] === 'deletecomment') {
        $commentId = $_GET['commentid'];
        echo $adminController->deleteComment($commentId);
    }
    elseif ($_GET['action'] === 'approvecomment') {
        $commentId = $_GET['commentid'];
        echo $adminController->approveComment($commentId, true);
    }
    elseif ($_GET['action'] === 'unvalidatecomment') {
        $commentId = $_GET['commentid'];
        echo $adminController->approveComment($commentId, false);
    }
    elseif ($_GET['action'] === 'deletepost') {
        $postId = $_GET['postid'];
        echo $adminController->deletePost($postId);
    }
    else { 
        echo $homeController->index();
    }
}
else { 
    echo $homeController->index();
}