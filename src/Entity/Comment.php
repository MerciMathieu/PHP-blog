<?php

namespace App\Entity;

use App\Entity\User;

Class Comment 
{

    private int $id;

    private int $postId;

    private string $content;

    private \DateTime $createdAt;

    private bool $isValidated;
    
    private ?\DateTime $updatedAt;
    
    private ?User $author;
    
    public function __construct(string $content, int $postId, User $author = null)
    {
        $this->content = $content;
        $this->postId = $postId;
        $this->author = $author;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt)
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

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
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
 
    public function getPostId(): int
    {
        return $this->postId;
    }

    public function setPostId(int $postId)
    {
        $this->postId = $postId;
    }
}