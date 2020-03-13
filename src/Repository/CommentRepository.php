<?php

namespace App\Repository;

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

    public function findAll(): Array
    {
        $req = $this->pdo->prepare("SELECT * from comment");
        $req->execute();
        
        $commentsArray = $req->fetchAll();

        /* $comment1 = new Comment(
            "Eustache Bourque",
            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, perspiciatis!"
        );

        $comment2 = new Comment(
            "Aubrette Pirouet",
            "Excepturi perferendis quos, eligendi officia velit obcaecati."
        ); 

        $comments = array($comment1, $comment2);*/

        $comments = [];
        foreach ($commentsArray as $commentArray) {
            $comment = new Comment(
                "Eustache Bourque",
                $commentArray['content']
            );

            $comments[] = $comment;
        }
        return $comments;

    }
}