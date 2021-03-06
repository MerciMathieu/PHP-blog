<?php

namespace App\Classes;

use PDO;

class ConnectDb
{
    private static $instance = null;
    private $conn;
    
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'blogphp';

    private function __construct()
    {
        $this->conn = new PDO(
            "mysql:host={$this->host};
                            dbname={$this->name}",
            $this->user,
            $this->pass,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
        );
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ConnectDb();
        }
    
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
