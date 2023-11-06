<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\Render;

class Cards
{
    private Render $render;

    public function __construct()
    {
        $this->render = new Render('Page');
    }

    public function generate(): void
    {
        $language = $_POST['language'];
        $words = array_values(array_filter(
            $_POST,
            fn($key) => preg_match('/^word-/', $key),
            ARRAY_FILTER_USE_KEY
        ));
        $words = array_values($words);
    }
}
