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

    public function editArticle()
    {
        $postRepository = new PostRepository($this->db);
        /* $post = $postRepository->find(1); */

        return $this->twig->render('admin/edit.html.twig', [
            'post' => $post,
        ]);
    }

    public function addArticle()
    {
        return $this->twig->render('admin/add.html.twig');
    }

    public function insertArticle()
    {
        $urlRedirect = '?action=admin';

        $title = $_POST['title'];
        $intro = $_POST['intro'];
        $content = $_POST['content'];
        $image = $_POST['image'];

        $post = new Post(
            $title,
            $intro,
            $content,
            $image
        );

        $postId = $this->postRepository->insert($post);   

        header("Location: $urlRedirect");
    }

    public function showComments()
    {

        $postRepository = new PostRepository($this->db);
        /* $post = $postRepository->find(1); */

        $commentRepository = new CommentRepository($this->db);
        $comments = $commentRepository->findAll();

        return $this->twig->render('admin/comments.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

}

