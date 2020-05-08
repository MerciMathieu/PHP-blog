<?php

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
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

    public function showPost(int $id)
    {
        $post = $this->postRepository->findOneById($id);
        
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
        $textColorError = null;
        $errors = [];
        
        if (isset($_POST['submit'])) {

            $post = $_POST;
            $success = true;

            if (!isset($_POST['lastname']) or empty($_POST['lastname']) or strlen($_POST['lastname']) <3 ) {
                $success = false;
                $errors['lastname'] = "Le nom doit contenir au moins 3 caractères";
                $textColorError['lastname'] = 'text-danger';
            }
            if (!isset($_POST['firstname']) or empty($_POST['firstname']) or strlen($_POST['firstname']) < 3 ) {
                $success = false;
                $errors['firstname'] = "Le prénom doit contenir au moins 3 caractères";
                $textColorError['firstname'] = 'text-danger';
            }
            if (!isset($_POST['email']) or empty($_POST['email']) or !preg_match ( " /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ " , $_POST['email'])) {
                $success = false;
                $errors['email'] = "L'email doit être valide";
                $textColorError['email'] = 'text-danger';
            }
            if (!isset($_POST['password']) or empty($_POST['password']) or strlen($_POST['password']) < 4) {
                $success = false;
                $errors['password'] = "Le mot de passe doit contenir au minimum 4 caractères";
                $textColorError['password'] = 'text-danger';
            }
            if ($_POST['confirm_password'] !== $_POST['password']) {
                $success = false;
                $errors['password'] = "Mot de passe différent";
                $textColorError['password_confirm'] = 'text-danger';
            }

            if ($success === true) {
                $user = new User(
                    $_POST['firstname'],
                    $_POST['lastname']
                );
                $user->setEmail(strtolower($_POST['email']));
                $user->setPassword(password_hash($_POST['password'], PASSWORD_DEFAULT));
                $this->userRepository->insert($user);

                $errors['success'] = "L'enregistrement s'est bien passé"; 
                $_SESSION['user'] = $user;
    
                header('Location: /blog');
            } 
        }

        return $this->twig->render('blog/register.html.twig', [
            'errors' => $errors,
            'postvariables' => $post,
            'textColorError' => $textColorError
        ]);
    }

    public function login()
    {
        $errors = [];
        $post = null;

        if (isset($_POST['submit'])) {

            $post = $_POST;
            $success = false;
            
            $user = $this->userRepository->findOneByEmail($_POST['email']);

            if ($user !== null and password_verify($_POST['password'], $user->getPassword())) {
                $success = true;
            } else {
                $errors['user'] = 'Le login/mot de passe est erroné';
            }

            if ($success === true) {
                $_SESSION['user'] = $user;
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
            if (isset($_SESSION['user'])) {
                $comment = new Comment(
                    $_POST['message'],
                    $post,
                    $_SESSION['user']
                );
                $this->commentRepository->insert($comment);
                header('Location:/post/'.$post->getId());
            }
        }
    }
}
