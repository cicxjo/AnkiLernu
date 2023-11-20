<?php

declare(strict_types = 1);

namespace App\Model\Manager;

use App\Model\Entity\Card as CardEntity;
use App\Model\PDOHandler;
use PDO;
use PDOException;

class Card
{
    private PDOHandler $pdoHandler;
    private string $table = 'cache';

    public function __construct()
    {
        $this->pdoHandler = new PDOHandler();
    }

    public function get(string $language, string $word): ?CardEntity
    {
        $sql = <<<HEREDOC
        SELECT * FROM {$this->table} WHERE word = :word AND country_code = :country_code
        HEREDOC;

        $values = [ 'word' => $word, 'country_code' => $language ];

        try {
            $card = $this->pdoHandler->execute($sql, $values)
                                     ->fetchAll(PDO::FETCH_CLASS, CardEntity::class);
        } catch (PDOException $exception) {
            $this->pdoHandler->debug($exception->getMessage());
        }

        return empty($card) ? null : $card[0];
    }

    public function insert(string $language, CardEntity $entity): void
    {
        $sql = <<<HEREDOC
        INSERT INTO {$this->table} (country_code, word, translation)
        VALUES (:country_code, :word, :translation)
        HEREDOC;

        $values = [
            'country_code' => $language,
            'word' => $entity->getWord(),
            'translation' => $entity->getTranslation()
        ];

        try {
            $this->pdoHandler->execute($sql, $values);
        } catch (PDOException $exception) {
            $this->pdoHandler->debug($exception->getMessage());
        }
    }

    public function update(CardEntity $cardEntity, string $language): void
    {
        $sql = <<<HEREDOC
        UPDATE {$this->table}
        SET translation = :translation, sync_at = :sync_at
        WHERE word = :word AND country_code = :country_code
        HEREDOC;

        $values = [
            'word' => $cardEntity->getWord(),
            'translation' => $cardEntity->getTranslation(),
            'sync_at' => $cardEntity->getSyncAt(),
            'country_code' => $language,
        ];

        try {
            $this->pdoHandler->execute($sql, $values);
        } catch (PDOException $exception) {
            $this->pdoHandler->debug($exception->getMessage());
        }
    }
}
