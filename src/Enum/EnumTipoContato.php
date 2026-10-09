<?php

namespace Src\Enum;

/**
 * Enumerado de tipos de contato
 * @author Djonatan R. de Oliveira
 * @package App
 * @subpackage Enum
 */
enum EnumTipoContato: int
{
    case TELEFONE = 1;
    case EMAIL    = 2;

    public function getDescricao(): string
    {
        return match ($this) {
            self::TELEFONE => 'Telefone',
            self::EMAIL    => 'E-mail',
        };
    }

}