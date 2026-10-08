<?php

$env = static function (string $key, string $default): string {
    $value = $_ENV[$key] ?? getenv($key);

    return $value === false || $value === '' ? $default : (string) $value;
};

return [
    'driver'   => $env('DB_DRIVER', 'pdo_pgsql'),
    'host'     => $env('DB_HOST', 'localhost'),
    'port'     => (int) $env('DB_PORT', '5432'),
    'dbname'   => $env('DB_NAME', 'contatos'),
    'user'     => $env('DB_USER', 'contatos'),
    'password' => $env('DB_PASSWORD', 'contatos'),
    'charset'  => 'utf8',
];