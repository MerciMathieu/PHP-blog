<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\User;

Class Comment 
{
    public int $id;

    public ?User $author = null;

    public string $content;

    public \DateTime $createdAt;

    public ?\DateTime $updatedAt = null;

    public bool $isValidated;
    
    public function __construct(string $content, int $postId, User $author)
    {
        $this->content = $content;
        $this->postId = $postId;
        $this->author = $author;
    }
}