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
        $req = $this->pdo->prepare(
            "SELECT p.id, p.title, p.intro, p.content ,p.created_at, p.updated_at, p.image_url,
                    u.first_name, u.last_name,
                    (SELECT COUNT(*) FROM comment c WHERE c.post_id = p.id AND c.is_validated=0) AS unvalidated_comments_count
            FROM   post p
            JOIN   user u
            ON     p.user_id = u.id
            ORDER BY id desc"
        );
        $req->execute();
                                
        $postsArrayFromDb = $req->fetchAll();
        $posts = [];
        foreach ($postsArrayFromDb as $postFromDb) {
            $hasUnvalidatedComments = $postFromDb['unvalidated_comments_count'] > 0 ? true : false;

            $author = new User(
                $postFromDb['first_name'],
                $postFromDb['last_name']
            );
            $post = new Post(
                $postFromDb['title'],
                $postFromDb['intro'],
                $postFromDb['content'],
                $postFromDb['image_url'],
                $author
            );
            $post->setId($postFromDb['id']);
            $post->setCreatedAt(new \DateTime($postFromDb['created_at']));
            $post->setUpdatedAt($postFromDb['updated_at'] ? new \DateTime($postFromDb['updated_at']) : null);
            $post->setHasUnvalidatedComments($hasUnvalidatedComments);
            $posts[] = $post;
        }
        return $posts;
    }

    public function findOneById(int $postId): ?Post
    {
        $post = null;

        $req = $this->pdo->prepare(
            "SELECT p.id, p.user_id, p.title, p.intro, p.content, p.created_at, p.updated_at, p.image_url,
                                           u.first_name, u.last_name
                                    FROM   post p
                                    JOIN   user u
                                    ON     p.user_id = u.id
                                    WHERE  p.id = :post_id"
        );
        $req->execute(['post_id' => $postId]);

        $postFromDb = $req->fetch();

        if ($postFromDb) {
            $author = new User(
                $postFromDb['first_name'],
                $postFromDb['last_name']
            );
            $author->setId($postFromDb['user_id']);
    
            $post = new Post(
                $postFromDb['title'],
                $postFromDb['intro'],
                $postFromDb['content'],
                $postFromDb['image_url'],
                $author
            );
            $post->setId($postFromDb['id']);
            $post->setCreatedAt(new \DateTime($postFromDb['created_at']));
            $post->setUpdatedAt($postFromDb['updated_at'] ? new \DateTime($postFromDb['updated_at']) : null);
        }
        
        return $post;
    }

    public function insert(Post $post): int
    {
        $req = $this->pdo->prepare("INSERT INTO post (user_id, title, intro, content, image_url) 
                                    VALUES (:user_id, :title, :intro, :content, :image_url)");

        $req->execute([
            'user_id' => $post->getAuthor()->getId(),
            'title' => $post->getTitle(),
            'intro' => $post->getIntro(),
            'content' =>$post->getContent(),
            'image_url' => $post->getImageUrl()
        ]);

        return $this->pdo->lastInsertId();
    }

    public function edit(Post $post): void
    {
        $req = $this->pdo->prepare("UPDATE post 
                                    SET title = :title, intro = :intro, content = :content, image_url = :image_url
                                    WHERE id = :post_id");

        $req->execute([
            'title' => $post->getTitle(),
            'intro' => $post->getIntro(),
            'content' =>$post->getContent(),
            'image_url' => $post->getImageUrl(),
            'post_id' => $post->getId()
        ]);
    }

    public function delete(Post $post): void
    {
        $req = $this->pdo->prepare("DELETE from post WHERE id = :id");
        $req->execute(['id' => $post->getId()]);
    }
}
