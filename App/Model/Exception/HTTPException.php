<?php

declare(strict_types = 1);

namespace App\Model\Exception;

use Exception;

class HTTPException extends Exception
{
    public function __construct(int $code)
    {
        $this->code = $code;
    }
}
