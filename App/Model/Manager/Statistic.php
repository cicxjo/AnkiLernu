<?php

declare(strict_types = 1);

namespace App\Model\Manager;

use App\Model\PDOHandler;
use PDO;
use PDOException;

class Statistic
{
    private PDOHandler $pdoHandler;
    private string $table = 'cache';

    public function __construct()
    {
        $this->pdoHandler = new PDOHandler();
    }

    public function get(): ?array
    {
        $sql = <<<HEREDOC
        SELECT
        (SELECT COUNT(translation) FROM {$this->table}) as translation,
        (SELECT COUNT(DISTINCT(country_code)) FROM {$this->table}) as country_code
        HEREDOC;

        try {
            $statistics = $this->pdoHandler->execute($sql)->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            $this->pdoHandler->debug($exception->getMessage());
        }

        return $statistics;
    }
}
