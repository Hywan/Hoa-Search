<?php

require_once dirname(dirname(__DIR__)) .
             DIRECTORY_SEPARATOR . 'Data' .
             DIRECTORY_SEPARATOR . 'Core.link.php';

require_once dirname(dirname(__DIR__)) .
    DIRECTORY_SEPARATOR . 'vendor' .
    DIRECTORY_SEPARATOR . 'autoload.php';

use Hoa\Core;
use Hoa\Dispatcher;
use Hoa\Router;

Core::enableErrorHandler();
Core::enableExceptionHandler();

$dispatcher = new Dispatcher\ClassMethod([
    'synchronous.call'  => 'Application\Controller\(:call:U:)',
    'synchronous.able'  => '(:able:U:)Action',
    'asynchronous.call' => '(:%synchronous.call:)',
    'asynchronous.able' => '(:%synchronous.able:)'
]);

$router = new Router\Http();
$router
    ->get_post(
        'se',
        '/(?<language>\w{2}).*',
        'search',
        'default',
        []
    );

try {

    $dispatcher->dispatch($router);
}
catch ( Core\Exception $e ) {

    echo 'error';

    var_dump($e->getMessage());
}
