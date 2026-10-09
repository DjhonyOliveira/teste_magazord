<?php

use Src\Core\Request;
use Src\Core\Response;
use Src\Core\Router;

$root = dirname(__DIR__);

require_once $root .'/vendor/autoload.php';

$entityManager = require $root . '/config/bootstrap.php';

$router = new Router();

(require $root . '/config/routes.php')($router);

$request = Request::fromGlobals();

try{
    $router->dispatch($request, $entityManager);
} catch (Throwable $exception){
    error_log(sprintf(
        '[%s] %s in %s:%d',
        get_class($exception),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine()
    ));

    if($request->method() === 'GET'){
        http_response_code(500);
        echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="utf-8"><title>Erro</title><link rel="stylesheet" href="/assets/css/app.css"></head><body><main><h1>Ocorreu um erro inesperado</h1><p class="texto-explicativo">Tente novamente em alguns instantes. Se o problema persistir, contate o suporte.</p></main></body></html>';
    } else {
        Response::json([
            'sucesso' => false,
            'erros'   => ['Ocorreu um erro inesperado. Tente novamente.'],
        ], 500);
    }
}