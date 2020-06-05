<?php

$autoload = __DIR__ . '/../vendor/autoload.php';
require_once $autoload;

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

$uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

if (isset($uri)) {
    if ($uri === '/') {
        $homeController->index();
    } elseif ($uri === '/email/confirmation') {
        $homeController->displayConfirmationSendMail();
    } elseif ($uri === '/commentaire/confirmation') {
        $blogController->displayConfirmationSendComment();
    } elseif ($uri === '/blog') {
        $blogController->index();
    } elseif (preg_match('/^\/post\/(\d+)$/', $uri, $matches)) {
        $postId = (int)$matches[1];
        $blogController->showPost($postId);
    } elseif ($uri === '/admin/posts') {
        $adminController->index();
    } elseif (preg_match('/^\/admin\/edit\/post\/(\d+)$/', $uri, $matches)) {
        $postId = (int)$matches[1];
        $adminController->editPost($postId);
    } elseif ($uri === '/admin/post/add') {
        $adminController->addPost();
    } elseif (preg_match('/^\/admin\/delete\/post\/(\d+)$/', $uri, $matches)) {
        $postId = (int)$matches[1];
        $adminController->deletePost($postId);
    } elseif (preg_match('/^\/admin\/moderate\/comments\/post\/(\d+)$/', $uri, $matches)) {
        $postId = (int)$matches[1];
        $adminController->showCommentsFromPost($postId);
    } elseif (preg_match('/^\/admin\/delete\/comment\/(\d+)$/', $uri, $matches)) {
        $commentId = (int)$matches[1];
        $adminController->deleteComment($commentId);
    } elseif (preg_match('/^\/admin\/approve\/comment\/(\d+)$/', $uri, $matches)) {
        $commentId = (int)$matches[1];
        $adminController->approveComment($commentId, true);
    } elseif (preg_match('/^\/admin\/unvalidate\/comment\/(\d+)$/', $uri, $matches)) {
        $commentId = (int)$matches[1];
        $adminController->approveComment($commentId, false);
    } elseif ($uri === '/login') {
        $blogController->login();
    } elseif ($uri === '/admin/login') {
        $adminController->login();
    } elseif ($uri === '/blog/register') {
        $blogController->register();
    } elseif ($uri === '/logout') {
        $blogController->logout();
    } else {
        $homeController->displayError(404);
    }
} else {
    $homeController->displayError(500);
}
