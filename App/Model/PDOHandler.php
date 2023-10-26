<?php

declare(strict_types = 1);

namespace App\Model;

use PDO;
use PDOException;
use PDOStatement;

class PDOHandler
{
    private PDO $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                'mysql:host=localhost;dbname=ankilernu;charset=utf8mb4',
                'root',
                'toor',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $exception) {
            echo('<pre>' . var_dump($exception->getMessage()) . '</pre>');
            die();
        }
    }

    public function execute(string $sql, ?array $args = null): PDOStatement
    {
        $statement = $this->pdo->prepare($sql);
        $args ? $statement->execute($args) : $statement->execute();

        return $statement;
    }
}
