<?php

namespace Src\Core;

/**
 * Gerenciador de request do sistema
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage Core
 */
class Request
{
    private const METODOS_SOBRESCREVIVEIS = ['PUT', 'DELETE'];

    private readonly string $method;
    private readonly string $path;

    public function __construct(
        string $method,
        string $path,
        private readonly array $query = [],
        private readonly array $body = [],
    ) {
        $this->method = self::resolveMethod($method, $body);
        $this->path = '/' . trim($path, '/');
    }

    public static function fromGlobals(): self
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        return new self(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            rawurldecode(is_string($path) ? $path : '/'),
            $_GET,
            $_POST,
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function all(): array
    {
        $body = $this->body;
        unset($body['_method']);

        return $body;
    }

    private static function resolveMethod(string $method, array $body): string
    {
        $method = strtoupper($method);
        $override = strtoupper((string) ($body['_method'] ?? ''));

        if ($method === 'POST' && in_array($override, self::METODOS_SOBRESCREVIVEIS, true)) {
            return $override;
        }

        return $method;
    }
}