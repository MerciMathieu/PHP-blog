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

    public function findAllByPostId($id): array
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
                $author,
                $commentFromDb['content']
            );
            $comment->setId($commentFromDb['id']);
            $comment->setCreatedAt(new \DateTime($commentFromDb['createdAt']));
            $comment->setUpdatedAt(new \DateTime($commentFromDb['updatedAt']));
            $comment->setIsValidated($commentFromDb['isValidated']);

            $comments[] = $comment;
        }
        return $comments;
    }
}