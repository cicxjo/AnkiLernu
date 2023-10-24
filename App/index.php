<?php

declare(strict_types = 1);

require_once(__DIR__ . '/Autoloader.php');
App\Autoloader::register();

$router = new App\Model\Routing\Router();

try {
    $router->run();
} catch (App\Model\Exception\HTTPException $exception) {
}
