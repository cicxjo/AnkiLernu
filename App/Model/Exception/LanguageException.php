<?php

declare(strict_types = 1);

namespace App\Model\Exception;

use Exception;

class LanguageException extends Exception
{
    private string $word;

    public function __construct()
    {
        $this->message = 'Language is not valid.';
    }

    public function getWord(): string
    {
        return $this->word;
    }
}
