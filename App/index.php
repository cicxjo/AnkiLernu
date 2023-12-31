<?php

declare(strict_types = 1);

require_once(__DIR__ . '/Autoloader.php');
App\Autoloader::register();

$router = new App\Model\Routing\Router();
$router->addRoute('/', ['GET'], ['App\Controller\Form', 'show'])
       ->addRoute('/deck.tsv', ['POST'], ['App\Controller\Deck', 'generate'])
       ->addRoute('/template/word', ['GET'], ['App\Controller\Form', 'getWordField']);

try {
    $router->run();
} catch (App\Model\Exception\HTTPException $exception) {
    (new App\Controller\HTTPError())->send($exception->getCode());
}
