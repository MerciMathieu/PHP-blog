<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

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
$homeController = new HomeController($twig, $postRepository, $commentRepository, $userRepository); 
$blogController = new BlogController($twig, $postRepository, $commentRepository, $userRepository);
$adminController = new AdminController($twig, $postRepository, $commentRepository, $userRepository);
/**** /Controllers ****/

if ($_SERVER['REQUEST_URI'] === '/') {
    echo $homeController->index();
}
elseif ($_SERVER['REQUEST_URI'] === '/blog') {
    echo $blogController->index();
}
elseif (preg_match('/^\/post\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $postId = (int)$matches[1];
    echo $blogController->showPost($postId);
}
elseif ($_SERVER['REQUEST_URI'] === '/admin/posts') {
    echo $adminController->index();
}
elseif (preg_match('/^\/admin\/edit\/post\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $postId = (int)$matches[1];
    echo $adminController->editPost($postId);
}
elseif ($_SERVER['REQUEST_URI'] === '/admin/post/add') {
    echo $adminController->addPost();
}
elseif (preg_match('/^\/admin\/delete\/post\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $postId = (int)$matches[1];
    echo $adminController->deletePost($postId);
}
elseif (preg_match('/^\/admin\/moderate\/comments\/post\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $postId = (int)$matches[1];
    echo $adminController->showCommentsFromPost($postId);
}
elseif (preg_match('/^\/admin\/delete\/comment\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $commentId = (int)$matches[1];
    echo $adminController->deleteComment($commentId);
}
elseif (preg_match('/^\/admin\/approve\/comment\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $commentId = (int)$matches[1];
    echo $adminController->approveComment($commentId, true);
}
elseif (preg_match('/^\/admin\/unvalidate\/comment\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $commentId = (int)$matches[1];
    echo $adminController->approveComment($commentId, false);
}
elseif ($_SERVER['REQUEST_URI'] === '/login') {
    echo $blogController->login();
}
elseif ($_SERVER['REQUEST_URI'] === '/register') {
    echo $blogController->register();
}
else { 
    echo $homeController->index();
}