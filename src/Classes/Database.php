<?php

namespace App\Classes;

use PDO;
use Exception;

class Database
{

    private static ?\PDO $db;

    public static function connect()
    {
        if (!self::$db) {

            self::$db = new PDO(
                'mysql:host=localhost;dbname=blogphp;charset=utf8',
                'root',
                ''
            );

            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$db;
    }
}
