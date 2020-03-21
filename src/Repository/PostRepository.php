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

        $title = $post->getTitle();
        $intro = $post->getIntro();
        $content = $post->getContent();
        $image = $post->getImageUrl();

        $req->bindParam('title', $title);
        $req->bindParam('intro', $intro);
        $req->bindParam('content', $content);
        $req->bindParam('imageUrl', $image);

        $req->execute();

        /* RECUPERER LA DERNIERE ENTREE EN BASE ( ID ) 
        POUR RENVOYER VERS LA PAGE DE L ARTICLE INSERE */
        return 1;
    }
}