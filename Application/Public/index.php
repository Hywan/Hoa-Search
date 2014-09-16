<?php

require_once dirname(dirname(__DIR__)) .
             DIRECTORY_SEPARATOR . 'Data' .
             DIRECTORY_SEPARATOR . 'Core.link.php';

require_once dirname(dirname(__DIR__)) .
    DIRECTORY_SEPARATOR . 'vendor' .
    DIRECTORY_SEPARATOR . 'autoload.php';

\Hoa\Core::enableErrorHandler();
\Hoa\Core::enableExceptionHandler();

from('Hoa')
-> import('Dispatcher.Basic')
-> import('Router.Http');

from('Application')
-> import('Controller.Generic');

$dispatcher = new \Hoa\Dispatcher\Basic();
$router = new \Hoa\Router\Http();

$router
    ->post(
        'se',
        '/(?<language>\w{2}).*',
        'search',
        'default',
        array()
    );

try {

    $dispatcher->dispatch($router);
}
catch ( \Hoa\Core\Exception $e ) {

    $router->route('/En/Error.html');
    $rule                                                = &$router->getTheRule();
    $rule[\Hoa\Router\Http::RULE_VARIABLES]['exception'] = $e;
    $dispatcher->dispatch($router);
}
