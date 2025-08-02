<?php

namespace App\Services;

class FilterService
{
    protected static $operators = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '<>'
    ];

    public static function parse(string $str) 
    {
        [$field, $op, $param] = explode('.', $str);

        $op = self::$operators[$op];

        return [$field, $op, $param];
    }
}