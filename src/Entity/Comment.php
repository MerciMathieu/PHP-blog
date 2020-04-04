<?php

namespace App\Entity;

use App\Entity\User;

Class Comment 
{

    private int $id;

    private string $content;

    private \DateTime $createdAt;

    private bool $isValidated;
    
    private ?\DateTime $updatedAt;
    
    private ?User $author;

    private Post $post;
    
    public function __construct(string $content, Post $post, User $author = null)
    {
        $this->content = $content;
        $this->post = $post;
        $this->author = $author;
    }

    public function getupdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setupdatedAt(?\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getIsValidated(): bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated)
    {
        $this->isValidated = $isValidated;
    }

    public function getcreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setcreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author)
    {
        $this->author = $author;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id)
    {
        $this->id = $id;
    }
 
    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post)
    {
        $this->post = $post;
    }
}