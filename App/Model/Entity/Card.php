<?php

declare(strict_types = 1);

namespace App\Model\Entity;

class Card
{
    private string $word;
    private string $translation;
    private string $inserted_at;
    private string $sync_at;
    private string $country_code;

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

    public function getSyncAt(): string
    {
        return $this->sync_at;
    }

    public function setSyncAt(string $sync_at): self
    {
        $this->sync_at = $sync_at;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    public function setCountryCode(string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }
}
