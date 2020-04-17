<?php

namespace App\Entity;

Class Post {


    private int $id;
    
    private string $title;

    private string $intro;

    private string $content;

    private \DateTime $createdAt;

    private ?\DateTime $updatedAt;

    private ?string $imageUrl;
    
    private ?User $author;



    public function __construct(
                                string $title, 
                                string $intro,
                                string $content,
                                string $imageUrl = null,
                                User $author = null)
    {
        $this->title = $title;
        $this->intro = $intro;
        $this->content = $content;
        $this->imageUrl = $imageUrl;
        $this->author = $author;
    }


    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getIntro(): string
    {
        return $this->intro;
    }

    public function setIntro(string $intro)
    {
        $this->intro = $intro;
    }
 
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
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
 
    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getHasUnvalidatedComments(): bool
    {
        return $this->hasUnvalidatedComments;
    }

    public function setHasUnvalidatedComments(bool $hasUnvalidatedComments)
    {
        $this->hasUnvalidatedComments = $hasUnvalidatedComments;
    }
}