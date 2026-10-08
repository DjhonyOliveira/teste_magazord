<?php

use Src\Core\Request;
use Src\Core\Router;

$root = dirname(__DIR__);

require_once $root .'/vendor/autoload.php';

$router = new Router();

(require $root . '/config/routes.php')($router);

try{
    $router->dispatch(Request::fromGlobals());
} catch (Throwable $exception){
    echo 'erro';
}