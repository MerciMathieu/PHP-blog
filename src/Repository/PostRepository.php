<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;

class PostRepository
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
        $req = $this->pdo->prepare("SELECT p.id, p.title,p.intro,p.content,p.createdAt,p.updatedAt,p.imageUrl,
                                           u.firstName,u.lastName
                                    FROM   post p
                                    JOIN   user u
                                    ON     p.userId = u.id");
        $req->execute();
        
        $postsArrayFromDb = $req->fetchAll();
        $posts = [];
        foreach ($postsArrayFromDb as $postFromDb) {
            
            $author = new User(
                $postFromDb['firstName'],
                $postFromDb['lastName']
            );
            $post = new Post(
                $postFromDb['id'],
                $postFromDb['title'],
                $postFromDb['intro'],
                $postFromDb['content'],
                new \DateTime($postFromDb['createdAt']),
                new \DateTime($postFromDb['updatedAt']),
                $postFromDb['imageUrl'],
                $author
            );
            $posts[] = $post;
        }
        return $posts;
    }

    public function findOneById(int $id): Post
    {
        $req = $this->pdo->prepare("SELECT p.id, p.title,p.intro,p.content,p.createdAt,p.updatedAt,p.imageUrl,
                                           u.firstName,u.lastName
                                    FROM   post p
                                    JOIN   user u
                                    ON     p.userId = u.id
                                    WHERE  p.id = $id"
                                    );
        $req->execute();
        $postFromDb = $req->fetch();

        $author = new User(
            $postFromDb['firstName'],
            $postFromDb['lastName']
        );

        $post = new Post(
            $postFromDb['id'],
            $postFromDb['title'],
            $postFromDb['intro'],
            $postFromDb['content'],
            new \DateTime($postFromDb['createdAt']),
            new \DateTime($postFromDb['updatedAt']),
            $postFromDb['imageUrl'],
            $author
        );


        return $post;
    }
}