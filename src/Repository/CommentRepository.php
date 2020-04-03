<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Comment;

class CommentRepository
{

    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findOneByID(int $id): Comment
    {
        $req = $this->pdo->prepare("SELECT *
                                    FROM   comment c
                                    WHERE  c.id = $id"
                                    );
        $req->execute();
        $commentFromDb = $req->fetch();

        $comment = new Comment(
            $commentFromDb['content'],
            $commentFromDb['post_id']
        );
        $comment->setId($commentFromDb['id']);
        
        return $comment;
    }

    public function findAllByPostId(int $id, bool $showApproved): array
    {
        $req = $this->pdo->prepare("SELECT c.id, c.content, c.created_at, c.updated_at, c.is_validated, 
                                           u.first_name, u.last_name
                                    FROM   comment c
                                    JOIN   user u
                                    ON     c.user_id = u.id
                                    WHERE  c.post_id = $id 
                                    AND    c.is_validated = '$showApproved'
                                    ORDER BY id desc");
        $req->execute();

        $commentsArrayFromDb = $req->fetchAll();
        
        $comments = [];
        foreach ($commentsArrayFromDb as $commentFromDb) {

            $author = new User(
                $commentFromDb['first_name'],
                $commentFromDb['last_name']
            );

            $comment = new Comment(
                $commentFromDb['content'],
                $id,
                $author
            );
            $comment->setId($commentFromDb['id']);
            $comment->setcreatedAt(new \DateTime($commentFromDb['created_at']));
            $comment->setupdatedAt(new \DateTime($commentFromDb['updated_at']));
            $comment->setIsValidated($commentFromDb['is_validated']);

            $comments[] = $comment;
        }
        return $comments;
    }

    public function insert(Comment $comment): void
    {
        $content = $comment->getContent();
        $postId = $comment->getPostId();

        $sql = "INSERT INTO comment (post_id, content) 
                VALUES (:post_id, :content)";

        $req = $this->pdo->prepare($sql);

        $req->execute(array(
            'content' => $content,
            'post_id' => $postId
        ));
    }

    public function approve(Comment $comment): void
    {
        $boolApproved = $comment->getIsValidated();

        if ($boolApproved == '' || $boolApproved === false) {
            $boolApproved = 0;
        }
        
        $req = $this->pdo->prepare("UPDATE comment SET is_validated=:is_validated WHERE id = :id");
        $req->execute([
            'id' => $comment->getId(),
            'is_validated' => $boolApproved 
            ]);
    }

    public function delete(Comment $comment): void
    {
        $req = $this->pdo->prepare("DELETE from comment WHERE id = :id");
        $req->execute(['id' => $comment->getId()]);
    }
}