<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Controller\AbstractController;

class AdminController extends AbstractController
{
    public function index()
    {
        $posts = $this->postRepository->findAll();
        return $this->twig->render('admin/admin.html.twig', [
            'posts' => $posts
        ]);
    }

    public function login()
    {
        $errors = [];
        $success = false;

        if ($this->isAdmin()) {
            header('Location: /admin/posts');
        }

        if (isset($_POST['submit'])) {

            $user = $this->userRepository->findOneByEmail($_POST['email']);

<<<<<<< HEAD
            if ($user === null or !password_verify($_POST['password'], $user->getPassword())) {
                $errors['user'] = 'Le login/mot de passe est erroné'; 
            }

            if ($user->getIsAdmin() === false) {
                $errors['admin'] = "Vous devez être administrateur pour entrer ici!";
            }
            
            if (empty($errors)) {
                $_SESSION['user'] = $user;
                header('Location: /admin/posts');
=======
            if ($user) {
                if (password_verify($_POST['password'], $user->getPassword())) {
                    $_SESSION['user'] = $user;
    
                    if ($user->getIsAdmin()) {
                        $success = true;
                    } else {
                        return $this->displayError(403);
                    }
                } else {
                    $errors[] = "Le mot de passe est invalide.";
                }
            } else {
                $errors[] = "Cet utilisateur n'existe pas!";
>>>>>>> #23-custom-error-pages
            }
        }

        if ($success === true) {
            Header('Location: /admin/posts');
        }

        return $this->twig->render('admin/login.html.twig', [
            'errors' => $errors
        ]);
    }

    public function addPost()
    {
        if (isset($_POST['submit'])) {
            if ($this->isAdmin()) {
                $post = new Post(
                    $_POST['title'],
                    $_POST['intro'],
                    $_POST['content'],
                    $_POST['image'],
                    $_SESSION['user']
                );
                $postId = $this->postRepository->insert($post);
                
                header("Location:/admin/edit/post/$postId");
            }
        }
        return $this->twig->render('admin/add.html.twig');
    }

    public function editPost(int $id)
    {
        $post = $this->postRepository->findOneById($id);

        if (isset($_POST['submit'])) {
            if ($this->isAdmin()
            and $this->getCurrentUser()->getId() === $post->getAuthor()->getId()) {
                $post->setTitle($_POST['title']);
                $post->setIntro($_POST['intro']);
                $post->setContent($_POST['content']);
                $post->setImageUrl($_POST['image']);

                $this->postRepository->edit($post);

                header('Location: /admin/posts');
            }
        }
        return $this->twig->render('admin/edit.html.twig', [
            'post' => $post
        ]);
    }

    public function deletePost(int $id): void
    {
        if (isset($_POST['delete'])) {
            $post = $this->postRepository->findOneById($id);

            if ($this->isAdmin()
            and $this->getCurrentUser()->getId() === $post->getAuthor()->getId()) {
                $this->postRepository->delete($post);

                header('Location:/admin/posts');
            }
        }
    }
    
    public function showCommentsFromPost(int $id)
    {
        $post = $this->postRepository->findOneById($id);
        $approvedComments = $this->commentRepository->findAllByPost($post, true);
        $unvalidatedComments = $this->commentRepository->findAllByPost($post, false);
    
        return $this->twig->render('admin/comments.html.twig', [
            'post' => $post,
            'approvedComments' => $approvedComments,
            'unvalidatedComments' => $unvalidatedComments
        ]);
    }

    public function deleteComment(int $id): void
    {
        if (isset($_POST['delete'])) {
            $comment = $this->commentRepository->findOneById($id);
            $this->commentRepository->delete($comment);

            header('Location:/admin/moderate/comments/post/'.$comment->getPost()->getId());
        }
    }

    public function approveComment(int $id, bool $validate): void
    {
        if (isset($_POST['unvalidate']) || isset($_POST['approve'])) {
            $comment = $this->commentRepository->findOneById($id);
            $comment->setIsValidated($validate);
            $this->commentRepository->approve($comment);

            header('Location:/admin/moderate/comments/post/'.$comment->getPost()->getId());
        }
    }
}
