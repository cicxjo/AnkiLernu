<?php

declare(strict_types = 1);

namespace App\Model\Entity;

class Card
{
    private string $word;
    private string $translation;
    private string $inserted_at;
    private string $modified_at;

    public function getWord(): string
    {
        return $this->word;
    }

    public function setWord(string $word): self
    {
        $this->word = $word;

        return $this;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }

    public function setTranslation(string $translation): self
    {
        $this->translation = $translation;

        return $this;
    }

    public function getInsertedAt(): string
    {
        return $this->inserted_at;
    }

    public function setInsertedAt(string $inserted_at): self
    {
        $this->inserted_at = $inserted_at;

        return $this;
    }

    public function getModifiedAt(): string
    {
        return $this->modified_at;
    }

    public function setModifiedAt(string $modified_at): self
    {
        $this->modified_at = $modified_at;

        return $this;
    }
}
