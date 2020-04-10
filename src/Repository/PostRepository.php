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
            "SELECT p.id, p.title,p.intro,p.content,p.created_at,p.updated_at,p.image_url,
                    u.first_name,u.last_name,
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
            $post->setcreatedAt(new \DateTime($postFromDb['created_at']));
            $post->setupdatedAt($postFromDb['updated_at'] ? new \DateTime($postFromDb['updated_at']) : null);
            $post->setHasUnvalidatedComments($hasUnvalidatedComments);
            $posts[] = $post;
        }
        return $posts;
    }

    public function findOneById(int $id): Post
    {
        $req = $this->pdo->prepare("SELECT p.id, p.title,p.intro,p.content,p.created_at,p.updated_at,p.image_url,
                                           u.first_name,u.last_name
                                    FROM   post p
                                    JOIN   user u
                                    ON     p.user_id = u.id
                                    WHERE  p.id = $id"
                                    );
        $req->execute();
        $postFromDb = $req->fetch();

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
        $post->setcreatedAt(new \DateTime($postFromDb['created_at']));
        $post->setupdatedAt($postFromDb['updated_at'] ? new \DateTime($postFromDb['updated_at']) : null);
        
        return $post;
    }

    public function insert(Post $post): int
    {
        $req = $this->pdo->prepare("INSERT INTO post (title, intro, content, image_url) 
                                    VALUES (:title, :intro, :content, :image_url)");

        $req->execute([
            'title' => $post->getTitle(),
            'intro' => $post->getIntro(),
            'content' =>$post->getContent(),
            'image_url' => $post->getImageUrl()
        ]);

        return $this->pdo->lastInsertId();
    }

    public function edit(Post $post): void
    {
        $sql = "UPDATE post 
                SET title = :title, intro = :intro, content = :content, image_url = :image_url 
                WHERE id = :id";

        $req = $this->pdo->prepare($sql);

        $req->execute([
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'intro' => $post->getIntro(),
            'content' =>$post->getContent(),
            'image_url' => $post->getImageUrl()
        ]);
    }

    public function delete(Post $post): void
    {
        $req = $this->pdo->prepare("DELETE from post WHERE id = :id");
        $req->execute(['id' => $post->getId()]);
    }
}
