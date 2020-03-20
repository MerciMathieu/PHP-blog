<?php

namespace App\Entity;

Class Post {
    /**
     * @var int
     */
    private $id;

    /**
     * @var User
     */
    private $author;
    
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $intro;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $imageUrl;


    public function __construct( 
                                int $id,
                                string $title, 
                                string $intro,
                                string $content,
                                \DateTime $createdAt,
                                \DateTime $updatedAt = null,
                                string $imageUrl = null,
                                User $author)
    {
        $this->id = $id;
        $this->title = $title;
        $this->intro = $intro;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
 
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
 
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
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
}