<?php

declare(strict_types = 1);

namespace App\Model;

class Config
{
    private array $database;

    public function __construct()
    {
        $config = realpath(getcwd() . '/config.ini');
        $config = parse_ini_file($config, true, INI_SCANNER_TYPED);

        $this->database['host'] = $config['database']['host'];
        $this->database['name'] = $config['database']['name'];
        $this->database['user'] = $config['database']['user'];
        $this->database['password'] = $config['database']['password'];
    }

    public function getDatabase(): array
    {
        return $this->database;
    }
}
