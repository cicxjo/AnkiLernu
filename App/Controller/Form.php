<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Data\Languages;
use App\Model\Render;

class Form
{
    private Render $render;

    public function __construct()
    {
        $this->render = new Render('Page');
    }

    private function whitespace(string $string): bool
    {
        return ctype_space($string);
    }

    public function show(): void
    {
        $vars = [
            'title' => 'Home',
            'languages' => Languages::$all,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['language']) || !key_exists($_POST['language'], Languages::$all)) {
                $vars['languageFalse'] = true;
            } else {
                $vars['selectedLanguage'] = $_POST['language'];
            }

            $words = array_filter($_POST, function ($key) {
                return preg_match('/^word-/', $key);
            }, ARRAY_FILTER_USE_KEY);
            $words = array_values($words);

            if (empty($words)) {
                $vars['wordEmpty'] = true;
            }

            if (count($words) === 1 && (empty($words[0]) || $this->whitespace($words[0]))) {
                $vars['wordEmpty'] = true;
            }
        }

        $this->render->setTemplate('Form')
                     ->process($vars);
    }
}
