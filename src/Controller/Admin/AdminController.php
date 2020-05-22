<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Classes\Session;
use App\Controller\AbstractController;

class AdminController extends AbstractController
{
    public function index()
    {
        if ($this->isAdmin() === false) {
            return $this->displayError(403);
        }

        $posts = $this->postRepository->findAll();

        return $this->twig->render('admin/admin.html.twig', [
            'posts' => $posts
        ]);
    }

    public function login()
    {
        $errors = [];

        if ($this->isAdmin()) {
            header('Location: /admin/posts');
        }

        if (isset($_POST['submit'])) {
            if (isset($_POST)) {
                $post = $_POST;
            }
            $email = htmlspecialchars($post['email']);
            $user = $this->userRepository->findOneByEmail($email);
            $password = htmlspecialchars($post['password']);

            if ($user === null or !password_verify($password, $user->getPassword())) {
                $errors['user'] = 'Le login/mot de passe est erroné';
            }

            if ($user and $user->getIsAdmin() === false) {
                return $this->displayError(403);
            }
            
            if (empty($errors)) {
                $session = new Session();
                $session->setSession('user', $user);
                header('Location: /admin/posts');
            }
        }

        return $this->twig->render('admin/login.html.twig', [
            'errors' => $errors
        ]);
    }

    public function addPost()
    {
        if ($this->isAdmin() === false) {
            return $this->displayError(403);
        }

        if (isset($_POST['submit'])) {
            if (isset($_POST)) {
                $postVariables = $_POST;
            }
            $title = htmlspecialchars($postVariables['title']);
            $intro = htmlspecialchars($postVariables['intro']);
            $content = htmlspecialchars($postVariables['content']);
            $image = htmlspecialchars($postVariables['image']);

            $session = new Session();
            $user = $session->getCurrent('user');

            $post = new Post(
                $title,
                $intro,
                $content,
                $image,
                $user
            );
            $postId = $this->postRepository->insert($post);

            if ($postId === null) {
                return $this->displayError(500);
            }

            header("Location:/admin/edit/post/$postId");
        }
        return $this->twig->render('admin/add.html.twig');
    }

    public function editPost(int $id)
    {
        $post = $this->postRepository->findOneById($id);

        if ($this->isAdmin() === false
        or $this->getCurrentUser()->getId() !== $post->getAuthor()->getId()) {
            return $this->displayError(403);
        }

        if (isset($_POST['submit'])) {
            if (isset($_POST)) {
                $postVariables = $_POST;
            }
            $title = htmlspecialchars($postVariables['title']);
            $intro = htmlspecialchars($postVariables['intro']);
            $content = htmlspecialchars($postVariables['content']);
            $image = htmlspecialchars($postVariables['image']);

            $post->setTitle($title);
            $post->setIntro($intro);
            $post->setContent($content);
            $post->setImageUrl($image);

            $postEdited = $this->postRepository->edit($post);

            if ($postEdited === false) {
                return $this->displayError(500);
            }

            header('Location: /admin/posts');
        }
        return $this->twig->render('admin/edit.html.twig', [
            'post' => $post
        ]);
    }

    public function deletePost(int $id)
    {
        if (isset($_POST['delete'])) {
            $post = $this->postRepository->findOneById($id);

            if ($this->isAdmin() === false
            or $this->getCurrentUser()->getId() !== $post->getAuthor()->getId()) {
                return $this->displayError(403);
            }

            $deletedPost = $this->postRepository->delete($post);

            if ($deletedPost === false) {
                return $this->displayError(500);
            }

            header('Location:/admin/posts');
        }
    }
    
    public function showCommentsFromPost(int $id)
    {
        $post = $this->postRepository->findOneById($id);

        if ($this->isAdmin() === false
        or $this->getCurrentUser()->getId() !== $post->getAuthor()->getId()) {
            return $this->displayError(403);
        }

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
            $post = $comment->getPost();
            
            $deletedComment = $this->commentRepository->delete($comment);

            if ($this->isAdmin() === false
            or $this->getCurrentUser()->getId() !== $post->getAuthor()->getId()) {
                return $this->displayError(403);
            }

            if ($deletedComment === false) {
                return $this->displayError(500);
            }

            header('Location:/admin/moderate/comments/post/'.$comment->getPost()->getId());
        }
    }

    public function approveComment(int $id, bool $validate)
    {
        if (isset($_POST['unvalidate']) || isset($_POST['approve'])) {
            $comment = $this->commentRepository->findOneById($id);
            $post = $comment->getPost();

            $comment->setIsValidated($validate);
            
            $approveComment = $this->commentRepository->approve($comment);

            if ($this->isAdmin() === false
            or $this->getCurrentUser()->getId() !== $post->getAuthor()->getId()) {
                return $this->displayError(403);
            }

            if ($approveComment === false) {
                return $this->displayError(500);
            }

            header('Location:/admin/moderate/comments/post/'.$comment->getPost()->getId());
        }
    }
}
