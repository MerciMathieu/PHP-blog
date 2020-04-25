<?php 

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Controller\AbstractController;

Class AdminController extends AbstractController
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
        if ($this->getCurrentUser() and $this->getCurrentUser()->getIsAdmin()) {
            return $this->index();
        } 

        if (isset($_POST['submit'])) {
            $user = $this->userRepository->findOneByEmail($_POST['email']);

            if (password_verify($_POST['password'], $user->getPassword())) {
                $_SESSION['user'] = $user;

                if ($_SESSION['user']->getIsAdmin()) {
                    Header('Location: /admin/posts');
                } else {
                    var_dump('Vous devez être administrateur pour entrer ici!'); exit;
                }
            } else {
                var_dump('Le mot de passe est invalide.'); exit;
            }
        }

        return $this->twig->render('admin/login.html.twig');
    }

    public function addPost()
    {
        if (isset($_POST['submit'])) {
            if ($this->getCurrentUser() and $this->getCurrentUser()->getIsAdmin()) {
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
            $current_user = $this->getCurrentUser();

            if ($current_user 
            and $current_user->getIsAdmin()
            and $current_user->getId() === $post->getAuthor()->getId()) 
            {
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
        if (isset($_POST['delete'])) 
        {
            $current_user = $this->getCurrentUser();
            $post = $this->postRepository->findOneById($id);

            if ($current_user 
            and $current_user->getIsAdmin()
            and $current_user->getId() === $post->getAuthor()->getId()) 
            {

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
        if (isset($_POST['delete'])) 
        {
            $comment = $this->commentRepository->findOneById($id);
            $this->commentRepository->delete($comment); 

            header('Location:/admin/moderate/comments/post/'.$comment->getPost()->getId());
        }
    }

    public function approveComment(int $id, bool $validate)
    {
        if (isset($_POST['unvalidate']) || isset($_POST['approve'])) 
        {
            $comment = $this->commentRepository->findOneById($id);
            $comment->setIsValidated($validate); 
            $this->commentRepository->approve($comment);

            header('Location:/admin/moderate/comments/post/'.$comment->getPost()->getId());
        }
    }
}

