<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Data\Languages;
use App\Model\Exception\HTTPException;
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
        $vars = [
            'title' => 'Home',
            'languages' => Languages::$all,
        ];

        $this->render->setTemplate('Form')
                     ->process($vars);
    }

    public function getWordField()
    {
        isset($_POST['id']) && ctype_digit($_POST['id'])
            ? $this->render->disableLayout()
                           ->setTemplate('WordField')
                           ->process(['id' => $_POST['id']])
            : throw new HTTPException(400);
    }
}
