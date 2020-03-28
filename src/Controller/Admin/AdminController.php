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

    public function addPost()
    {
        if (!isset($_POST['submit'])) {
            return $this->twig->render('admin/add.html.twig');
        }
        
        $post = new Post(
            $_POST['title'],
            $_POST['intro'],
            $_POST['content'],
            $_POST['image']
        );
        $postId = $this->postRepository->insert($post); 
        
        header("Location:/?action=editpost&id=$postId");
    }

    public function editPost(int $id)
    {
        $post = $this->postRepository->findOneById($id);

        if (!isset($_POST['submit'])) {
            return $this->twig->render('admin/edit.html.twig', [
                'post' => $post
            ]);
        }

        $post->setTitle($_POST['title']);
        $post->setIntro($_POST['intro']);
        $post->setContent($_POST['content']);
        $post->setImageUrl($_POST['image']);

        $this->postRepository->edit($post);

        header("Location:/?action=admin");
    } 

    public function deletePost(int $id)
    {
        if (!isset($_POST['delete'])) {
            return $this->twig->render('admin/admin.html.twig');
        }
        
        $post = $this->postRepository->findOneById($id);
        $this->postRepository->delete($post);   

        header('Location:?action=admin');
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

