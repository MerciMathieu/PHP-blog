<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

use App\Classes\Session;
use App\Classes\ConnectDb;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
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
$session = new Session();
$currentUser = $session->getCurrent('user') ? $session->getCurrent('user') : null;
$twig->addGlobal('current_user', $currentUser);
/**** /TWIG ****/

/**** Database ****/
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();
/*****************/

/**** Repositories ****/
$postRepository = new PostRepository($conn);
$commentRepository = new CommentRepository($conn);
$userRepository = new UserRepository($conn);
/**** /Repositories ****/

/**** Controllers ****/
$homeController = new HomeController($twig, $postRepository, $commentRepository, $userRepository);
$blogController = new BlogController($twig, $postRepository, $commentRepository, $userRepository);
$adminController = new AdminController($twig, $postRepository, $commentRepository, $userRepository);
/**** /Controllers ****/

$uri = $_SERVER['REQUEST_URI'];

if (isset($uri)) {
    if ($uri === '/') {
        echo $homeController->index();
    } elseif ($uri === '/email/confirmation') {
        echo $homeController->displayConfirmationSendMail();
    } elseif ($uri === '/commentaire/confirmation') {
        echo $blogController->displayConfirmationSendComment();
    } elseif ($uri === '/blog') {
        echo $blogController->index();
    } elseif (preg_match('/^\/post\/(\d+)$/', $uri, $matches)) {
        $postId = (int)$matches[1];
        echo $blogController->showPost(htmlspecialchars($postId));
    } elseif ($uri === '/admin/posts') {
        echo $adminController->index();
    } elseif (preg_match('/^\/admin\/edit\/post\/(\d+)$/', $uri, $matches)) {
        $postId = (int)$matches[1];
        echo $adminController->editPost(htmlspecialchars($postId));
    } elseif ($uri === '/admin/post/add') {
        echo $adminController->addPost();
    } elseif (preg_match('/^\/admin\/delete\/post\/(\d+)$/', $uri, $matches)) {
        $postId = (int)$matches[1];
        echo $adminController->deletePost(htmlspecialchars($postId));
    } elseif (preg_match('/^\/admin\/moderate\/comments\/post\/(\d+)$/', $uri, $matches)) {
        $postId = (int)$matches[1];
        echo $adminController->showCommentsFromPost(htmlspecialchars($postId));
    } elseif (preg_match('/^\/admin\/delete\/comment\/(\d+)$/', $uri, $matches)) {
        $commentId = (int)$matches[1];
        echo $adminController->deleteComment(htmlspecialchars($commentId));
    } elseif (preg_match('/^\/admin\/approve\/comment\/(\d+)$/', $uri, $matches)) {
        $commentId = (int)$matches[1];
        echo $adminController->approveComment(htmlspecialchars($commentId), true);
    } elseif (preg_match('/^\/admin\/unvalidate\/comment\/(\d+)$/', $uri, $matches)) {
        $commentId = (int)$matches[1];
        echo $adminController->approveComment(htmlspecialchars($commentId), false);
    } elseif ($uri === '/login') {
        echo $blogController->login();
    } elseif ($uri === '/admin/login') {
        echo $adminController->login();
    } elseif ($uri === '/blog/register') {
        echo $blogController->register();
    } elseif ($uri === '/logout') {
        echo $blogController->logout();
    } else {
        echo $homeController->displayError(404);
    }
} else {
    echo $homeController->displayError(500);
}
