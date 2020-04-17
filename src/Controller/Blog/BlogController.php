<?php 

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;

Class BlogController
{
    private \Twig\Environment $twig;

    private PostRepository $postRepository;

    private CommentRepository $commentRepository;

    private UserRepository $userRepository;

    public function __construct(\Twig\Environment $twig, 
                                 PostRepository $postRepository, 
                                 CommentRepository $commentRepository,
                                 UserRepository $userRepository)
    {
        $this->twig = $twig;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
    }

    public function index() 
    {
        $posts = $this->postRepository->findAll();

        return $this->twig->render('blog/blog.html.twig', [
            'posts' => $posts,
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

    public function login()
    {
        return $this->twig->render('blog/login.html.twig');
    }

    public function register()
    {
        if (isset($_POST['submit'])) {

            $user = new User(
                $_POST['firstname'],
                $_POST['lastname']
            );
            $user->setEmail($_POST['email']);
            $user->setPassword(password_hash($_POST['password'], PASSWORD_BCRYPT));
            if ($_POST['confirm_password'] === $_POST['password']) {
                $this->userRepository->insert($user);
                header('Location: /blog');
            } else {
                echo "<script>alert(\"Le mot de passe est différent\")</script>";
            }
        }
        return $this->twig->render('blog/register.html.twig');
    }

    private function insertComment(Post $post) 
    {   
        if (isset($_POST['submit'])) {

            $comment = new Comment(
                $_POST['message'],
                $post
            );
    
            $this->commentRepository->insert($comment); 
            
            header('Location:/post/'.$post->getId());
        }
    }
}

