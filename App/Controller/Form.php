<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Data\Languages;
use App\Model\Exception\HTTPException;
use App\Model\Manager\Statistic as StatisticManager;
use App\Model\Render;

class Form
{
    private Render $render;

    public function __construct()
    {
        $this->render = new Render('Page');
    }

    public function show(): void
    {

        $statistics = (new StatisticManager())->get();

        $vars = [
            'title' => 'AnkiLernu — Your personal Esperanto learning companion',
            'h1' => 'AnkiLernu',
            'languages' => Languages::$all,
            'statistics' => $statistics,
        ];

        $this->render->setTemplate('Form')
                     ->process($vars);
    }

    public function getWordField(): void
    {
        isset($_GET['id']) && ctype_digit($_GET['id'])
            ? $this->render->disableLayout()
                           ->setTemplate('WordField')
                           ->process(['id' => $_GET['id']])
            : throw new HTTPException(400);
    }
}
