<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;

class CommentRepository
{
    private \PDO $pdo;

    private PostRepository $postRepository;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->postRepository = new PostRepository($pdo);
    }

    public function findOneByID(int $id): Comment
    {
        $req = $this->pdo->prepare(
            "SELECT *
                                    FROM   comment c
                                    WHERE  c.id = :id"
        );

        $req->execute(['id' => $id]);

        $commentFromDb = $req->fetch();

        $post = $this->postRepository->findOneByID($commentFromDb['post_id']);

        $comment = new Comment(
            $commentFromDb['content'],
            $post
        );
        
        $comment->setId($commentFromDb['id']);
        
        return $comment;
    }

    public function findAllByPost(Post $post, bool $showApproved): array
    {
        $req = $this->pdo->prepare("SELECT c.id, c.content, c.created_at, c.is_validated, 
                                           u.first_name, u.last_name
                                    FROM   comment c
                                    JOIN   user u
                                    ON     c.user_id = u.id
                                    WHERE  c.post_id = :id
                                    AND    c.is_validated = :is_validated
                                    ORDER BY id desc");
        $req->execute([
            'id' => $post->getId(),
            'is_validated' => (int)$showApproved
            ]);

        $commentsArrayFromDb = $req->fetchAll();
        
        $comments = [];
        foreach ($commentsArrayFromDb as $commentFromDb) {
            $author = new User(
                $commentFromDb['first_name'],
                $commentFromDb['last_name']
            );

            $comment = new Comment(
                $commentFromDb['content'],
                $post,
                $author
            );
            $comment->setId($commentFromDb['id']);
            $comment->setCreatedAt(new \DateTime($commentFromDb['created_at']));
            $comment->setIsValidated($commentFromDb['is_validated']);

            $comments[] = $comment;
        }
        return $comments;
    }

    public function insert(Comment $comment): void
    {
        $req = $this->pdo->prepare("INSERT INTO comment (user_id, post_id, content) VALUES (:user_id, :post_id, :content)");
        $req->execute(array(
            'content' => $comment->getContent(),
            'post_id' => $comment->getPost()->getId(),
            'user_id' => $comment->getAuthor()->getId()
        ));
    }

    public function approve(Comment $comment): void
    {
        $req = $this->pdo->prepare("UPDATE comment SET is_validated=:is_validated WHERE id = :id");
        $req->execute([
            'id' => $comment->getId(),
            'is_validated' => (int)$comment->getIsValidated()
            ]);
    }

    public function delete(Comment $comment): void
    {
        $req = $this->pdo->prepare("DELETE from comment WHERE id = :id");
        $req->execute(['id' => $comment->getId()]);
    }
}
