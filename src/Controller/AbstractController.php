<?php

namespace App\Controller;

use App\Entity\User;
use App\Classes\Session;
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
    ) {
        $this->twig = $twig;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
    }

    protected function getCurrentUser(): ?User
    {
        $user = null;
        $session = new Session();

        if ($session->getCurrent('user')) {
            $user = $session->getCurrent('user');
        }
        return $user;
    }

    public function logout(): void
    {
        session_destroy();
        header('Location:/');
    }

    protected function isAdmin(): bool
    {
        $currentUser = $this->getCurrentUser();

        if ($currentUser === null or $currentUser->getIsAdmin() === false) {
            return false;
        }
        return true;
    }

    public function displayError(int $errorCode)
    {
        if (http_response_code()) {
            http_response_code($errorCode);

            return $this->render("error/error" . $errorCode . ".html.twig");
        }
    }

    protected function getPostVariables(): ?array
    {
        $post = null;
        if ($_POST) {
            $post = $_POST;
        }
        return $post;
    }

    protected function render(string $template, array $params = []): void
    {
        echo $this->twig->render($template, $params);
    }

}
