<?php 

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;

Class BlogController
{
    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @var \PDO
     */
    private $db;

    public function __construct(\Twig\Environment $twig, \PDO $db)
    {
        $this->twig = $twig;
        $this->db = $db;
    }

    public function index() 
    {
        $postRepository = new PostRepository($this->db);
        $posts = $postRepository->findAll();

        return $this->twig->render('blog/blog.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function showPost($id) 
    {  
        $postRepository = new PostRepository($this->db);
        $post = $postRepository->findOneById($id);

        $commentRepository = new CommentRepository($this->db);
        $comments = $commentRepository->findAll();
        
        return $this->twig->render('blog/showpost.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }
}

