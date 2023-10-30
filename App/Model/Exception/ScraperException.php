<?php

declare(strict_types = 1);

namespace App\Model\Exception;

use Exception;

/*
 * Property int $code != 0 when Curl got an HTTP code != 200
 */
class ScraperException extends Exception
{
    public function __construct(string $message, int $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
