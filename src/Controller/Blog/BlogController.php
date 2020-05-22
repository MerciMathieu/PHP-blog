<?php

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Classes\Session;
use App\Controller\AbstractController;

class BlogController extends AbstractController
{
    public function index()
    {
        $posts = $this->postRepository->findAll();
        return $this->twig->render('blog/blog.html.twig', [
            'posts' => $posts
        ]);
    }

    public function showPost(int $postId)
    {
        $post = $this->postRepository->findOneById($postId);
        $comments = [];

        if ($post === null) {
            return $this->displayError(404);
        }

        if (isset($_POST['submit'])) {
            $this->insertComment($post);
        }
        
        $comments = $this->commentRepository->findAllByPost($post, true);
        
        return $this->twig->render('blog/showpost.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    public function register()
    {
        $post = null;
        $errors = [];
        
        if (isset($_POST['submit'])) {
            $post = $_POST;
            $firstName = htmlspecialchars($post['firstname']);
            $lastName = htmlspecialchars($post['lastname']);
            $email = htmlspecialchars($post['email']);
            $password = htmlspecialchars($post['password']);
            $confirmPassword = htmlspecialchars($post['confirm_password']);

            if (!isset($lastName) or empty($lastName) or strlen($lastName) <3) {
                $errors['lastname'] = "Le nom doit contenir au moins 3 caractères";
            }
            if (!isset($firstName) or empty($firstName) or strlen($firstName) < 3) {
                $errors['firstname'] = "Le prénom doit contenir au moins 3 caractères";
            }
            if (!isset($email) or empty($email) or !preg_match(" /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ ", $email)) {
                $errors['email'] = "L'email doit être valide";
            }
            if (!isset($password) or empty($password) or strlen($password) < 4) {
                $errors['password'] = "Le mot de passe doit contenir au minimum 4 caractères";
            }
            if ($confirmPassword !== $password) {
                $errors['password'] = "Mot de passe différent";
            }

            $user = new User(
                $firstName,
                $lastName
            );
            $user->setEmail(strtolower($email));
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $user->setIsAdmin(0);

            $this->userRepository->insert($user);

            if ($user === null) {
                return $this->displayError(500);
            } else {
                $session = new Session();
                $session->setSession('user', $user);

                header('Location: /blog');
            }
        }

        return $this->twig->render('blog/register.html.twig', [
            'errors' => $errors,
            'postvariables' => $post
        ]);
    }

    public function login()
    {
        $post = null;
        $errors = [];

        if (isset($_POST['submit'])) {
            $post = $_POST;
            $email = $post['email'];
            $user = $this->userRepository->findOneByEmail($email);
            $password = $post['password'];

            if ($user === null or !password_verify($password, $user->getPassword())) {
                $errors['user'] = 'Le login/mot de passe est erroné';
            }

            $session = new Session();
            $session->setSession('user', $user);
            $user = $session->getCurrent('user');

            if ($user === null) {
                return $this->displayError(500);
            } else {
                header('Location: /blog');
            }
        }

        return $this->twig->render('blog/login.html.twig', [
            'errors' => $errors,
            'postvariables' => $post
        ]);
    }

    private function insertComment(Post $post)
    {
        if (isset($_POST['submit'])) {
            $session = new Session();
            $user = $session->getCurrent('user');
            $postVariables = $_POST;
            $message = htmlspecialchars($postVariables['message']);

            if ($user) {
                $textComment = htmlspecialchars($message);
                $comment = new Comment(
                    $textComment,
                    $post,
                    $user
                );

                $insertedComment = $this->commentRepository->insert($comment);

                if ($insertedComment === false) {
                    return $this->displayError(500);
                } else {
                    header('Location:/commentaire/confirmation');
                }
            }
        }
    }

    public function displayConfirmationSendComment()
    {
        return  $this->twig->render('confirm/confirmComment.html.twig');
    }
}
