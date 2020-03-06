<?php 

namespace App\Controller\Admin;

use App\Repository\PostRepository;
use App\Repository\CommentRepository;

Class AdminController
{
    /**
     * @var \Twig\Environment
     */
    private $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index() 
    {
        $postRepository = new PostRepository;
        $posts = $postRepository->findAll();

        return $this->twig->render('admin/admin.html.twig', [
            'posts' => $posts
        ]);
    }

    public function editArticle()
    {
        $postRepository = new PostRepository();
        $post = $postRepository->find(1);

        return $this->twig->render('admin/edit.html.twig', [
            'post' => $post,
        ]);
    }

    public function addArticle()
    {
        return $this->twig->render('admin/add.html.twig', [

        ]);
    }

    public function deleteArticle()
    {
        
    }

    public function showComments()
    {
        $postRepository = new PostRepository();
        $post = $postRepository->find(1);

        $commentRepository = new CommentRepository();
        $comments = $commentRepository->findAll();

        return $this->twig->render('admin/comments.html.twig', [
            'comments' => $comments,
            'post' => $post
        ]);
    }

}

