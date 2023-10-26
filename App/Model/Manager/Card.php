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

    public function __construct()
    {
        $this->pdoHandler = new PDOHandler();
    }

    public function get(string $language, string $word): ?CardEntity
    {
        $sql = <<<HEREDOC
        SELECT * FROM {$language} WHERE word = :word
        HEREDOC;

        $values = [ 'word' => $word ];

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
        INSERT INTO {$language} (word, translation)
        VALUES (:word, :translation)
        HEREDOC;

        $values = [
            'word' => $entity->getWord(),
            'translation' => $entity->getTranslation()
        ];

        try {
            $this->pdoHandler->execute($sql, $values);
        } catch (PDOException $exception) {
            $this->pdoHandler->debug($exception->getMessage());
        }
    }

    public function update(string $language, string $word, string $translation): void
    {
        $sql = <<<HEREDOC
        UPDATE {$language}
        SET translation = :translation
        WHERE word = :word
        HEREDOC;

        $values = [
            'word' => $word,
            'translation' => $translation
        ];

        try {
            $this->pdoHandler->execute($sql, $values);
        } catch (PDOException $exception) {
            $this->pdoHandler->debug($exception->getMessage());
        }
    }
}
