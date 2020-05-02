<?php

namespace App\Entity;

use App\Entity\User;

class Comment
{
    private int $id;

    private string $content;

    private \DateTime $createdAt;

    private bool $isValidated;

    private Post $post;
    
    private ?User $author;


    public function __construct(string $content, Post $post, User $author = null)
    {
        $this->content = $content;
        $this->post = $post;
        $this->author = $author;
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

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
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
