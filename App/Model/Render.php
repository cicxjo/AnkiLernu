<?php

declare(strict_types = 1);

namespace App\Model;

class Render
{
    private string $path;
    private ?string $layout;
    private string $template;

    public function __construct(string $layout)
    {
        $this->path = realpath(getcwd() . '/View/');
        $this->layout = realpath($this->path . '/Layout/' . $layout . '.phtml');
    }

    public function setTemplate(string $template): self
    {
        $this->template = realpath($this->path . '/Template/' . $template . '.phtml');

        return $this;
    }

    public function disableLayout(): self
    {
        $this->layout = null;

        return $this;
    }

    public function process(array $vars = []): void
    {
        foreach ($vars as $key => $value) {
            $$key = $value;
        }

        ob_start();
        require_once($this->template);
        $vars = ob_get_clean();

        if ($this->layout) {
            require_once($this->layout);
        } else {
            echo $vars;
        }
    }
}
