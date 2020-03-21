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
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    public function __construct(\Twig\Environment $twig, 
                                 PostRepository $postRepository, 
                                 CommentRepository $commentRepository)
    {
        $this->twig = $twig;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    public function index() 
    {
        $posts = $this->postRepository->findAll();

        return $this->twig->render('blog/blog.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function showPost($id) 
    {  
        $post = $this->postRepository->findOneById($id);

        $comments = $this->commentRepository->findAllByPostId($id);

        return $this->twig->render('blog/showpost.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }
}

