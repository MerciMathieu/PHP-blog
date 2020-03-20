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

    public function findAll(): array
    {
        $req = $this->pdo->prepare("SELECT c.content, c.createdAt, c.updatedAt, c.isValidated, 
                                           u.firstName, u.lastName 
                                    FROM   comment c
                                    JOIN   user u
                                    ON     c.userId = u.id");
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
                $commentFromDb['content'],
                new \DateTime($commentFromDb['createdAt']),
                new \DateTime($commentFromDb['updatedAt']),
                $commentFromDb['isValidated']
            );
            
            $comments[] = $comment;
        }
        return $comments;
    }
}