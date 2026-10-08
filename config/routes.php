<?php

use Src\Controller\ControllerHome;
use Src\Core\Router;

return static function (Router $router){
    $router->get('/', [ControllerHome::class, 'list']);
};