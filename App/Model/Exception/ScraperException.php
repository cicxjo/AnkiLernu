<?php

declare(strict_types = 1);

namespace App\Model\Exception;

use Exception;

class ScraperException extends Exception
{
    private string $word;

    public function __construct(string $message, string $word, int $code = 0)
    {
        $this->message = $message;
        $this->word = $word;
        $this->code = $code;
    }

    public function getWord(): string
    {
        return $this->word;
    }
}
