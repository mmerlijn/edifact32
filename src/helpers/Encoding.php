<?php

namespace mmerlijn\msgEdifact32\helpers;

class Encoding
{
    protected static $charsEncoding = array("é" => 'e', "è" => 'e', "ë" => 'e', "ê" => 'e', "É" => 'E', "È" => 'E', "Ë" => 'E', "Ê" => 'E', "á" => 'a', "à" => 'a', "ä" => 'a', "â" => 'a', "å" => 'a', "Á" => 'A', "À" => 'A', "Ä" => 'A', "Â" => 'A', "Å" => 'A', "ó" => 'o', "ò" => 'o', "ö" => 'o', "ô" => 'o', "Ó" => 'O', "Ò" => 'O', "Ö" => 'O', "Ô" => 'O', "í" => 'i', "ì" => 'i', "ï" => 'i', "î" => 'i', "Í" => 'I', "Ì" => 'I', "Ï" => 'I', "Î" => 'I', "ú" => 'u', "ù" => 'u', "ü" => 'u', "û" => 'u', "Ú" => 'U', "Ù" => 'U', "Ü" => 'U', "Û" => 'U', "ý" => 'y', "ÿ" => 'y', "Ý" => 'Y', "ø" => 'o', "Ø" => 'O', "œ" => 'a', "Œ" => 'A', "Æ" => 'A', "ç" => 'c', "Ç" => 'C',"≥" => ">="
    , "≤" => "<=");
    protected static $segmentSeparator = '\n';
    protected static $fieldSeparator = '+';
    protected static $componentSeparator = ':';

    protected static $escapeChar = '\\';

    public static function setSeparator($separators = ['+', ':'])
    {
        static::$fieldSeparator = $separators[0];
        static::$componentSeparator = $separators[1];
        static::$escapeChar = $separators[3];
    }

    public static function getSegmentSeparator()
    {
        return static::$segmentSeparator;
    }

    public static function getFieldSeparator()
    {
        return static::$fieldSeparator;
    }

    public static function getComponentSeparator()
    {
        return static::$componentSeparator;
    }

    public static function getEscapeChar()
    {
        return static::$escapeChar;
    }
    public static function encode(string $string=""):string
    {
        return str_replace(array_keys(static::$charsEncoding), static::$charsEncoding, $string);
    }
}