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
                                    ON     p.userId = u.id
                                    ORDER BY id desc"
                                );
        $req->execute();
        
        $postsArrayFromDb = $req->fetchAll();
        $posts = [];
        foreach ($postsArrayFromDb as $postFromDb) {
            
            $author = new User(
                $postFromDb['firstName'],
                $postFromDb['lastName']
            );
            $post = new Post(
                $postFromDb['title'],
                $postFromDb['intro'],
                $postFromDb['content'],
                $postFromDb['imageUrl'],
                $author
            );
            $post->setId($postFromDb['id']);
            $post->setCreatedAt(new \DateTime($postFromDb['createdAt']));
            $post->setUpdatedAt(new \DateTime($postFromDb['updatedAt']));
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
            $postFromDb['title'],
            $postFromDb['intro'],
            $postFromDb['content'],
            $postFromDb['imageUrl'],
            $author
        );
        $post->setId($postFromDb['id']);
        $post->setCreatedAt(new \DateTime($postFromDb['createdAt']));
        $post->setUpdatedAt(new \DateTime($postFromDb['updatedAt']));
        
        return $post;
    }

    public function insert(Post $post): int
    {
        $sql = "INSERT INTO post (title, intro, content, imageUrl) 
                VALUES (:title, :intro, :content, :imageUrl)";

        $req = $this->pdo->prepare($sql);

        $req->execute([
            'title' => $post->getTitle(),
            'intro' => $post->getIntro(),
            'content' =>$post->getContent(),
            'imageUrl' => $post->getImageUrl()
        ]);

        return $this->pdo->lastInsertId();
    }

    public function edit(Post $post): void
    {
        $sql = "UPDATE post 
                SET title = :title, intro = :intro, content = :content, imageUrl = :imageUrl 
                WHERE id = :id";

        $req = $this->pdo->prepare($sql);

        $req->execute([
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'intro' => $post->getIntro(),
            'content' =>$post->getContent(),
            'imageUrl' => $post->getImageUrl()
        ]);
    }

    public function delete(Post $post): void
    {
        $req = $this->pdo->prepare("DELETE from post WHERE id = :id");
        $req->execute(['id' => $post->getId()]);
    }
}