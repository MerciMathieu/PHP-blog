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

        if ($this->isAdmin()) {
            return $this->index();
        }

        if (isset($_POST['submit'])) {
            
            /* if (){
                $errors['email'][] = "L'adresse email n'est pas valide.";
            } */

            $user = $this->userRepository->findOneByEmail($_POST['email']);
            if ($user) {
                if (password_verify($_POST['password'], $user->getPassword())) {
                    $_SESSION['user'] = $user;
    
                    if ($_SESSION['user']->getIsAdmin()) {
                        Header('Location: /admin/posts');
                    } else {
                        $errors[] = "Vous devez être administrateur pour entrer ici!";
                    }
                } else {
                    $errors[] = "Le mot de passe est invalide.";
                }
            } else {
                $errors[] = "Cet utilisateur n'existe pas!";
            }
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

    public function deletePost(int $id)
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

    public function deleteComment(int $id)
    {
        if (isset($_POST['delete'])) {
            $comment = $this->commentRepository->findOneById($id);
            $this->commentRepository->delete($comment);

            header('Location:/admin/moderate/comments/post/'.$comment->getPost()->getId());
        }
    }

    public function approveComment(int $id, bool $validate)
    {
        if (isset($_POST['unvalidate']) || isset($_POST['approve'])) {
            $comment = $this->commentRepository->findOneById($id);
            $comment->setIsValidated($validate);
            $this->commentRepository->approve($comment);

            header('Location:/admin/moderate/comments/post/'.$comment->getPost()->getId());
        }
    }
}
