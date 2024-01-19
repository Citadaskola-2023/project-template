<?php

namespace App;

use PDO;

class Database
{
    private PDO $connection;

    public function __construct()
    {
        $dsn = 'mysql:host=mysql;port=3306;dbname=myapp;charset=utf8mb4';
        $this->connection = new PDO($dsn, 'root', 'root', [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function query(string $query)
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
