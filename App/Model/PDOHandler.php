<?php

declare(strict_types = 1);

namespace App\Model;

use PDO;
use PDOException;
use PDOStatement;

class PDOHandler
{
    private PDO $pdo;
    private bool $debug;

    public function __construct()
    {
        $db = (new Config())->getDatabase();

        $this->debug = $db['debug'];

        $dsn = 'mysql:';
        $dsn .= 'host='. $db['host'] . ';';
        $dsn .= 'dbname=' . $db['name'] . ';';
        $dsn .= 'charset=utf8mb4';

        try {
            $this->pdo = new PDO(
                $dsn,
                $db['user'],
                $db['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $exception) {
            $this->debug($exception->getMessage());
        }
    }

    public function debug(mixed ...$vars): void
    {
        if ($this->debug) {
            foreach ($vars as $var) {
                echo( '<pre>' . print_r($var, true) . '</pre>');
            }

            exit();
        }
    }

    public function execute(string $sql, ?array $args = null): PDOStatement
    {
        $statement = $this->pdo->prepare($sql);
        $args ? $statement->execute($args) : $statement->execute();

        return $statement;
    }
}
