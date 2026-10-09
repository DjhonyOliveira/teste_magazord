<?php

namespace Src\Core;

/**
 * Gerenciador de response do sistema
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage Core
 */
class Response
{
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}