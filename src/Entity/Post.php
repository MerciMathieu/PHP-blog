<?php

namespace App\Entity;

Class Post {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
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
     * @var
     */
    private $imageUrl;


    public function __construct(string $author, string $title, string $intro, string $content)
    {
        $this->author = $author;
        $this->title = $title;
        $this->intro = $intro;
        $this->content = $content;
        $this->createdAt = new \DateTime();
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
 
    public function getUpdatedAt()
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
 
    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function getImageUrl()
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