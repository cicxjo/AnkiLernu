<?php

declare(strict_types = 1);

namespace App\Model\Exception;

use Exception;

/*
 * Property int $code != 0 when Curl got an HTTP code != 200
 */
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
