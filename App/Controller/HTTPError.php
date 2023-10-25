<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\Render;

class HTTPError
{
    private Render $render;
    private array $codes = [
        404 => 'Not Found',
        405 => 'Method Not Allowed',
    ];

    public function __construct()
    {
        $this->render = new Render('Page');
    }

    public function send(int $code): void
    {
        http_response_code($code);

        $this->render->setTemplate('HTTPError')
                     ->process(['title' => $code . ' - ' . $this->codes[$code]]);
    }
}
