<?php

namespace Src\Enum;

/**
 * Enumerado de tipos de contato
 * @author Djonatan R. de Oliveira
 * @package App
 * @subpackage Enum
 */
Enum EnumTipoContato: string
{
    case TELEFONE = 'Telefone';
    case EMAIL    = 'E-mail';
    
    public static function getTipoContatoFromBool(bool $valor): string
    {
        return $valor ? self::TELEFONE->value : self::EMAIL->value;
    }

    public function ValidaTipoContato()
    {
        return $this === self::TELEFONE;
    }

}