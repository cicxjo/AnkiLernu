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

    public function show(): void
    {
        $this->render->setTemplate('Form')
                     ->process([
                        'title' => 'Home',
                        'languages' => $this->languages,
                    ]);
    }
}
