<?php 

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Controller\AbstractController;

Class BlogController extends AbstractController
{
   
    public function index() 
    {
        $currentUser = '';
        $posts = $this->postRepository->findAll();

        if ($this->getCurrentUser()) 
        {
            $currentUser = $this->getCurrentUser();
        }

        return $this->twig->render('blog/blog.html.twig', [
            'posts' => $posts,
            'current_user' => $currentUser
        ]);
    }

    public function showPost(int $id) 
    {  
        $post = $this->postRepository->findOneById($id);
        
        if (isset($_POST['submit'])) 
        {
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
        if (isset($_POST['submit'])) {

            if ($_POST['confirm_password'] !== $_POST['password']) 
            {
                var_dump("Mot de passe différent"); exit;
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
        return $this->twig->render('blog/register.html.twig');
    }

    public function login()
    {
        if (isset($_POST['submit'])) 
        {

            $user = $this->userRepository->findOneByEmail($_POST['email']);

            if (password_verify($_POST['password'], $user->getPassword())) 
            {
                $_SESSION['user'] = $user;
                header('Location: /blog');
            } else 
            {
                var_dump('Le mot de passe est invalide.'); exit;
            }
        }
        
        return $this->twig->render('blog/login.html.twig');
    }

    private function insertComment(Post $post) 
    {   
        if (isset($_POST['submit'])) 
        {
            if (isset($_SESSION['user'])) 
            {
                $comment = new Comment(
                    $_POST['message'],
                    $post,
                    $_SESSION['user']
                );

                $this->commentRepository->insert($comment); 

                header('Location:/post/'.$post->getId());
            }
            else {
                var_dump('Il faut être connecté pour pouvoir laisser un commentaire!'); exit;
            }
        }
    }
}

