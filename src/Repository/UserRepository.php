<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository
{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert(User $user): void
    {
        $req = $this->pdo->prepare("INSERT INTO user (first_name, last_name, email, password) VALUES         (:first_name, :last_name, :email, :password)");

        $req->execute([
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ]);
    }
}