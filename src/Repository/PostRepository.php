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
        $req = $this->pdo->prepare("SELECT p.id, p.title,p.intro,p.content,p.created_at,p.updated_at,p.image_url,p.has_unvalidated_comments,
                                           u.first_name,u.last_name
                                    FROM   post p
                                    JOIN   user u
                                    ON     p.user_id = u.id
                                    ORDER BY id desc"
                                );
                                $req->execute();
                                
        
        $postsArrayFromDb = $req->fetchAll();
        $posts = [];
        foreach ($postsArrayFromDb as $postFromDb) {

            $this->updateIfPostHasUnvalidatedComments($postFromDb['id']);

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
            $post->setupdatedAt(new \DateTime($postFromDb['updated_at']));
            $post->setHasUnvalidatedComments($postFromDb['has_unvalidated_comments']);
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
        $post->setupdatedAt(new \DateTime($postFromDb['updated_at']));
        
        return $post;
    }

    public function insert(Post $post): int
    {
        $sql = "INSERT INTO post (title, intro, content, image_url) 
                VALUES (:title, :intro, :content, :image_url)";

        $req = $this->pdo->prepare($sql);

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

    private function updateIfPostHasUnvalidatedComments(int $id): void
    {
        $hasUnvalidatedComments = 0;

        $sql = "SELECT COUNT(*)
                FROM   comment c
                JOIN   post p
                ON     p.id = c.post_id
                WHERE  p.id = $id
                AND    c.is_validated = 0";

        $req = $this->pdo->query($sql);
        $countUnvalidatedComments = $req->fetchColumn();

        if ($countUnvalidatedComments > 0) {
            $hasUnvalidatedComments = 1;           
        }

        $sql = "UPDATE post p
                SET has_unvalidated_comments = :has_unvalidated_comments
                WHERE p.id = :id";

        $req = $this->pdo->prepare($sql);

        $req->execute([
            'id' => $id,
            'has_unvalidated_comments' => $hasUnvalidatedComments
        ]);
    }
}
