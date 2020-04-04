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
        if (isset($_POST['submit'])) {
            $post = new Post(
                $_POST['title'],
                $_POST['intro'],
                $_POST['content'],
                $_POST['image']
            );
            $postId = $this->postRepository->insert($post); 
            
            header("Location:/?action=editpost&postid=$postId");
        }
        
        return $this->twig->render('admin/add.html.twig');
    }

    public function editPost(int $id)
    {
        $post = $this->postRepository->findOneById($id);

        if (isset($_POST['submit'])) {
            $post->setTitle($_POST['title']);
            $post->setIntro($_POST['intro']);
            $post->setContent($_POST['content']);
            $post->setImageUrl($_POST['image']);

            $this->postRepository->edit($post);

            header("Location:/?action=admin");
        }

        return $this->twig->render('admin/edit.html.twig', [
            'post' => $post
        ]);
    } 

    public function deletePost(int $id)
    {
        if (isset($_POST['delete'])) {
            $post = $this->postRepository->findOneById($id);
            $this->postRepository->delete($post);   

            header('Location:?action=admin');
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
        if (isset($_POST['delete'])) {

            $commentRepository = $this->commentRepository;
            
            $comment = $commentRepository->findOneById($id);
            $commentRepository->delete($comment); 

            header('Location:?action=moderate-post-comments&postid='.$comment->getPost()->getId());
        }
    }

    public function approveComment(int $id, bool $validate)
    {
        if (isset($_POST['unvalidate']) || isset($_POST['approve'])) {
            $commentRepository = $this->commentRepository;

            $comment = $commentRepository->findOneById($id);

            $comment->setIsValidated($validate); 
            $commentRepository->approve($comment);

            header('Location:?action=moderate-post-comments&postid='.$comment->getPost()->getId());
        }
    }

}

