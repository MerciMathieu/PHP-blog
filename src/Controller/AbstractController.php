<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;

abstract class AbstractController
{
    protected \Twig\Environment $twig;

    protected PostRepository $postRepository;

    protected CommentRepository $commentRepository;

    protected UserRepository $userRepository;

    public function __construct(
        \Twig\Environment $twig,
        PostRepository $postRepository,
        CommentRepository $commentRepository,
        UserRepository $userRepository
    )
    {
        $this->twig = $twig;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
    }

    protected function getCurrentUser(): ?User
    {
        $user = null;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }
        return $user;
    }

    public function logout(): void
    {
        session_destroy();
        header('Location:/');
    }

    public function isAdmin(): bool
    {
        $currentUser = $this->getCurrentUser();
        if ($currentUser and $currentUser->getIsAdmin() === true) {
            return true;
        }
        return false;
    }

    public function displayError(int $errorCode)
    {
        http_response_code($errorCode);
        return $this->twig->render("error/error" . $errorCode . ".html.twig");
    }
}
