<?php

namespace App\Classes;

use Exception;

class Database 
{
    public function connect()
    {
        $db = new PDO
        (
            'mysql:host=localhost;dbname=blogphp;charset=utf8', 
            'root', 
            ''
        );

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }
}