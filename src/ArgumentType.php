<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-05-30
 * Time: 23:46
 */

namespace Birsoz\Converter;


class ArgumentType
{

    protected $type;
    protected $className;

    CONST TYPE_INTEGER = 1;
    CONST TYPE_DECIMAL = 2;
    CONST TYPE_STRING = 3;
    CONST TYPE_DATE = 4;
    CONST TYPE_DATETIME = 6;


    CONST TYPE_LISTOF_INTEGER = 11;
    CONST TYPE_LISTOF_DECIMAL = 12;
    CONST TYPE_LISTOF_STRING = 13;
    CONST TYPE_LISTOF_DATE = 14;
    CONST TYPE_LISTOF_DATETIME = 16;


    CONST TYPE_OBJECT = 9;
    CONST TYPE_LISTOF_OBJECT = 19;


    private function __construct(int $type, Struct $struct = null)
    {
        $this->type = $type;
        if ($struct) {
            $this->struct = $struct;
        }
    }

    public static function Date(): ArgumentType
    {
        return new ArgumentType(self::TYPE_DATE);
    }

    public static function DateArray(): ArgumentType
    {
        return new ArgumentType(self::TYPE_LISTOF_DATE);
    }

    public static function Int(): ArgumentType
    {
        return new ArgumentType(self::TYPE_INTEGER);
    }

    public static function IntArray(): ArgumentType
    {
        return new ArgumentType(self::TYPE_LISTOF_INTEGER);
    }


    public static function Datetime(): ArgumentType
    {
        return new ArgumentType(self::TYPE_DATETIME);
    }

    public static function DatetimeArray(): ArgumentType
    {
        return new ArgumentType(self::TYPE_LISTOF_DATETIME);
    }


    public static function Decimal(): ArgumentType
    {
        return new ArgumentType(self::TYPE_DECIMAL);
    }

    public static function DecimalArray(): ArgumentType
    {
        return new ArgumentType(self::TYPE_LISTOF_DECIMAL);
    }

    public static function String(): ArgumentType
    {
        return new ArgumentType(self::TYPE_STRING);
    }

    public static function StringArray(): ArgumentType
    {
        return new ArgumentType(self::TYPE_LISTOF_STRING);
    }

    public static function Object(Struct $struct): ArgumentType
    {
        return new ArgumentType(self::TYPE_OBJECT, $struct);
    }

    public static function ObjectArray(Struct $struct): ArgumentType
    {
        return new ArgumentType(self::TYPE_LISTOF_OBJECT, $struct);
    }


}