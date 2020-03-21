<?php 

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;

Class AdminController
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

        return $this->twig->render('admin/admin.html.twig', [
            'posts' => $posts
        ]);
    }

    public function editPostForm($id)
    {
        $post = $this->postRepository->findOneById($id);

        return $this->twig->render('admin/edit.html.twig', [
            'post' => $post,
        ]);
    }

    public function addPostForm()
    {
        return $this->twig->render('admin/add.html.twig');
    }

    public function insertPost()
    {
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

        header("Location: /");
    }

    public function showCommentsFromPost($id)
    {
        $post = $this->postRepository->findOneById($id);
        $comments = $this->commentRepository->findAllByPostId($id);

        return $this->twig->render('admin/comments.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

}

