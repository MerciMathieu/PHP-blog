<?php

namespace App\Classes;

use PDO;
use Exception;

class Database
{
    public static function connect()
    {
        $db = new PDO(
            'mysql:host=localhost;dbname=blogphp;charset=utf8',
            'root',
            ''
        );

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }
}
