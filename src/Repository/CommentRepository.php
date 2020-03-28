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
            $commentFromDb['postId']
        );
        
        return $comment;
    }

    public function findAllByPostId(int $id): array
    {
        $req = $this->pdo->prepare("SELECT c.id, c.content, c.createdAt, c.updatedAt, c.isValidated, 
                                           u.firstName, u.lastName
                                    FROM   comment c
                                    JOIN   user u
                                    ON     c.userId = u.id
                                    WHERE  c.postId = $id
                                    ORDER BY id desc");
        $req->execute();
        
        $commentsArrayFromDb = $req->fetchAll();

        $comments = [];
        foreach ($commentsArrayFromDb as $commentFromDb) {

            $author = new User(
                $commentFromDb['firstName'],
                $commentFromDb['lastName']
            );

            $comment = new Comment(
                $commentFromDb['content'],
                $id,
                $author
            );
            $comment->setId($commentFromDb['id']);
            $comment->setCreatedAt(new \DateTime($commentFromDb['createdAt']));
            $comment->setUpdatedAt(new \DateTime($commentFromDb['updatedAt']));
            $comment->setIsValidated($commentFromDb['isValidated']);

            $comments[] = $comment;
        }
        return $comments;
    }

    public function insert(Comment $comment): void
    {
        $content = $comment->getContent();
        $postId = $comment->getPostId();

        $sql = "INSERT INTO comment (postId, content) 
                VALUES (:postId, :content)";

        $req = $this->pdo->prepare($sql);

        $req->execute(array(
            'content' => $content,
            'postId' => $postId
        ));

        header('Location:/');
    }

    public function delete(Comment $comment): void
    {
        $id = $comment->getId();
        $req = $this->pdo->prepare("DELETE from comment WHERE id = $id");
        $req->execute();

        header('Location:?action=admin');
    }
}