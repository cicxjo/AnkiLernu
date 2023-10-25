<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\Render;

class Form
{
    private Render $render;
    private array $languages = [
        'Bahasa Indonesia',
        'Català',
        'Česky',
        'Dansk',
        'Deutsch',
        'English',
        'Español',
        'Esperanto',
        'Euskara',
        'Français',
        'Gaeilge',
        'Gàidhlig',
        'Hornjoserbsce',
        'Hrvatski',
        'Italiano',
        'Kiswahili',
        'Lietuvių',
        'Magyar',
        'Nederlands',
        'Norsk bokmål',
        'Polski',
        'Português',
        'Română',
        'Runa Simi',
        'Slovenčina',
        'Slovenščina',
        'Suomi',
        'Svenska',
        'Tagalog',
        'Tiếng Việt',
        'Türkçe',
        'Ελληνικά',
        'Български',
        'Русский',
        'Српски / srpski',
        'Українська',
        'עברית',
        'اردو',
        'العربية',
        'فارسی',
        'हिन्दी',
        'ไทย',
        '한국어',
        '中文',
        '日本語',
        '简体中文',
    ];

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
            'languages' => $this->languages,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['language']) || !in_array($_POST['language'], $this->languages, true)) {
                $vars['languageFalse'] = true;
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
