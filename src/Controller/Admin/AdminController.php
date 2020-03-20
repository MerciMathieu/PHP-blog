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

        return $this->twig->render('admin/admin.html.twig', [
            'posts' => $posts
        ]);
    }

    public function editPostForm($id)
    {
        $postRepository = new PostRepository($this->db);
        $post = $postRepository->findOneById($id);

        return $this->twig->render('admin/edit.html.twig', [
            'post' => $post,
        ]);
    }

    public function addPostForm()
    {
        return $this->twig->render('admin/add.html.twig', [

        ]);
    }

    public function deleteArticle()
    {
        
    }

    public function showCommentsFromPost($id)
    {
        $postRepository = new PostRepository($this->db);
        $post = $postRepository->findOneById($id);

        $commentRepository = new CommentRepository($this->db);
        $comments = $commentRepository->findAllByPostId($id);

        return $this->twig->render('admin/comments.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

}

