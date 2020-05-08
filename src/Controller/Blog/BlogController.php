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
        $comments = [];
        $post = $this->postRepository->findOneById($id);

        if ($post !== null ) {
            if (isset($_POST['submit'])) {
                $this->insertComment($post);
            }
            $comments = $this->commentRepository->findAllByPost($post, true);
        } else {
            return $this->displayError(404);
        }
        
        
        return $this->twig->render('blog/showpost.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    public function register()
    {
        $errors = [];

        if (isset($_POST['submit'])) {
            if ($_POST['confirm_password'] !== $_POST['password']) {
                $errors['password'][] = "Mot de passe différent";
            }

            $user = new User(
                $_POST['firstname'],
                $_POST['lastname']
            );
            $user->setEmail(strtolower($_POST['email']));
            $user->setPassword(password_hash($_POST['password'], PASSWORD_DEFAULT));
            $this->userRepository->insert($user);

            return $this->index();
        }
        return $this->twig->render('blog/register.html.twig', [
            'errors' => $errors
        ]);
    }

    public function login()
    {
        $errors = [];
        if (isset($_POST['submit'])) {
            $user = $this->userRepository->findOneByEmail($_POST['email']);

            if (password_verify($_POST['password'], $user->getPassword())) {
                $_SESSION['user'] = $user;
                header('Location: /blog');
            } else {
                $errors['password'][] = 'Le mot de passe est invalide.';
            }
        }
        return $this->twig->render('blog/login.html.twig', [
            'errors' => $errors
        ]);
    }

    private function insertComment(Post $post): void
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
