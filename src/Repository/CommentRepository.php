<?php

namespace App\Repository;

use App\Entity\Comment;

class CommentRepository
{
    public function findAll(): Array
    {
        $comments = array();

        $comment1 = new Comment(
            "Eustache Bourque",
            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, perspiciatis!"
        );

        $comment2 = new Comment(
            "Aubrette Pirouet",
            "Excepturi perferendis quos, eligendi officia velit obcaecati."
        );

        $comments = array($comment1, $comment2);
        return $comments;

    }
}