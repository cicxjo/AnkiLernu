<?php

declare(strict_types = 1);

namespace App;

class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    private static function autoload(string $class): void
    {
        $file = str_replace(__NAMESPACE__ . '\\', '', $class);
        $file = str_replace('\\', '/', $file);
        $file = realpath(__DIR__ . '/' . $file . '.php');

        require_once($file);
    }
}
