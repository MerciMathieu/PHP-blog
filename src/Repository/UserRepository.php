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

    public function findOneByEmail(string $email) 
    {
        //TODO
        // EMAIL EN LOWERCASE
        $req = $this->pdo->prepare("SELECT *
                                    FROM   user u 
                                    WHERE  u.email = :email
                                    ");
        $req->execute(['email' => $email]);

        $userFromDb = $req->fetch();

        $user = new User(
            $userFromDb['first_name'],
            $userFromDb['last_name']
        );

        $user->setEmail($userFromDb['email']);
        $user->setPassword($userFromDb['password']);

        return $user;
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