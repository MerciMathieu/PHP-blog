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
        $this->render('blog/blog.html.twig', [
            'posts' => $posts
        ]);
    }

    public function showPost(int $postId)
    {
        $post = $this->postRepository->findOneById($postId);
        $comments = [];
        $postVariables = $this->getPostVariables();

        if ($post === null) {
            return $this->displayError(404);
        }

        if (isset($postVariables['submit'])) {
            $this->insertComment($post);
        }
        
        $comments = $this->commentRepository->findAllByPost($post, true);
        
        return $this->render('blog/showpost.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    public function register()
    {
        $errors = [];
        $postVariables = $this->getPostVariables();
        
        if (isset($postVariables['submit'])) {
            $firstName = htmlspecialchars($postVariables['firstname']);
            $lastName = htmlspecialchars($postVariables['lastname']);
            $email = htmlspecialchars($postVariables['email']);
            $password = htmlspecialchars($postVariables['password']);
            $confirmPassword = htmlspecialchars($postVariables['confirm_password']);

            $this->checkLastName($lastName, $errors);
            $this->checkFirstName($firstName, $errors);
            $this->checkEmail($email, $errors);
            $this->checkPassword($password, $errors);
            
            if ($confirmPassword !== $password) {
                $errors['password'] = "Mot de passe différent";
            }

            if (!$errors) {
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
                }
    
                $session = new Session();
                $session->setSession('user', $user);
    
                header('Location: /blog');
            }
        }

        return $this->render('blog/register.html.twig', [
            'errors' => $errors,
            'postvariables' => $postVariables
        ]);
    }

    public function login()
    {
        $errors = [];
        $postVariables = $this->getPostVariables();

        if (isset($postVariables['submit'])) {
            $email = $postVariables['email'];
            $user = $this->userRepository->findOneByEmail($email);
            $password = $postVariables['password'];

            if ($user === null or !password_verify($password, $user->getPassword())) {
                $errors['user'] = 'Le login/mot de passe est erroné';
            }

            if (!$errors) {
                $session = new Session();
                $session->setSession('user', $user);
                $user = $session->getCurrent('user');

                header('Location: /blog');
            }
        }

        return $this->render('blog/login.html.twig', [
            'errors' => $errors,
            'postvariables' => $postVariables
        ]);
    }

    private function checkLastName(string $lastName, array &$errors)
    {
        if (!isset($lastName) or empty($lastName) or strlen($lastName) <3) {
            $errors['lastname'] = "Le nom doit contenir au moins 3 caractères";
        }
    }

    private function checkFirstName(string $firstName, array &$errors)
    {
        if (!isset($firstName) or empty($firstName) or strlen($firstName) < 3) {
            $errors['firstname'] = "Le prénom doit contenir au moins 3 caractères";
        }
    }

    private function checkEmail(string $email, array &$errors)
    {
        if (!isset($email) or empty($email) or !preg_match(" /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ ", $email)) {
            $errors['email'] = "L'email doit être valide";
        }
    }

    private function checkPassword(string $password, array &$errors)
    {
        if (!isset($password) or empty($password) or strlen($password) < 4) {
            $errors['password'] = "Le mot de passe doit contenir au minimum 4 caractères";
        }
    }

    private function insertComment(Post $post)
    {
        $postVariables = $this->getPostVariables();

        if (isset($postVariables['submit'])) {
            $session = new Session();
            $user = $session->getCurrent('user');
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
                }

                header('Location:/commentaire/confirmation');
            }
        }
    }

    public function displayConfirmationSendComment()
    {
        return  $this->render('confirm/confirmComment.html.twig');
    }
}
