<?php

namespace App\Entity;

class User
{
    private int $identifier;

    private string $firstName;

    private string $lastName;

    private string $email;

    private string $password;
    
    private bool $isAdmin;
    

    public function __construct(
        string $firstName,
        string $lastName
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function setcreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function getId(): int
    {
        return $this->identifier;
    }

    public function setId(int $identifier)
    {
        $this->identifier =  $identifier;
    }
}
