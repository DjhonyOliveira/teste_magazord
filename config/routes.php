<?php

use Src\Controller\ControllerContato;
use Src\Controller\ControllerHome;
use Src\Controller\ControllerPessoa;
use Src\Core\Router;

return static function (Router $router){
    $router->get('/',               [ControllerHome::class, 'list']);

    $router->get('/pessoas',        [ControllerPessoa::class, 'list']);
    $router->get('/pessoas/criar',  [ControllerPessoa::class, 'create']);
    $router->post('/pessoas/criar', [ControllerPessoa::class, 'create']);

    $router->get('/pessoas/editar',     [ControllerPessoa::class, 'update']);
    $router->post('/pessoas/editar',    [ControllerPessoa::class, 'update']);
    $router->get('/pessoas/visualizar', [ControllerPessoa::class, 'show']);
    $router->delete('/pessoas',         [ControllerPessoa::class, 'delete']);

    $router->get('/contatos',        [ControllerContato::class, 'list']);
    $router->get('/contatos/criar',  [ControllerContato::class, 'create']);
    $router->post('/contatos/criar', [ControllerContato::class, 'create']);

    $router->get('/contatos/editar',     [ControllerContato::class, 'update']);
    $router->post('/contatos/editar',    [ControllerContato::class, 'update']);
    $router->get('/contatos/visualizar', [ControllerContato::class, 'show']);
    $router->delete('/contatos',         [ControllerContato::class, 'delete']);
};