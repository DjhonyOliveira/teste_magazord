<?php

namespace Src\Core;

use Closure;
use Exception;

/**
 * Roteador base do sistema
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage Core
 */
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, array $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    public function put(string $path, array $handler): self
    {
        return $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, array $handler): self
    {
        return $this->add('DELETE', $path, $handler);
    }

    public function add(string $method, string $path, array $handler): self
    {
        $this->routes[] = [
            'path'    => $path,
            'method'  => strtoupper($method),
            'handler' => $handler,
        ];

        return $this;
    }

    public function dispatch(Request $request, mixed ...$args){
        foreach($this->routes as $route){
            if($request->path() === $route['path'] && $request->method() === $route['method']){
                return $this->call($route['handler'], $request, ...$args);
            }
        }
    }

    private function call(array $handler, $request, mixed ...$args)
    {
        list($classe, $metodo) = $handler;

        if(class_exists($classe)){
            $controller = new $classe($request, ...$args);

            if(method_exists($controller, $metodo)){
                return $controller->$metodo();
            }
        }

        throw new Exception('Erro na solicitação, tente novamente');
    }

}