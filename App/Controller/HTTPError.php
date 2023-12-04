<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\Render;

class HTTPError
{
    private Render $render;
    private array $codes = [
        400 => 'Bad Request',
        404 => 'Resource not Found',
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
                     ->process([
                         'title' => 'AnkiLernu' . ' — ' . $this->codes[$code],
                         'h1' => 'AnkiLernu',
                         'h2' => $this->codes[$code],
                     ]);
    }
}
