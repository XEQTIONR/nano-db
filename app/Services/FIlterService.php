<?php

namespace App\Services;

use Illuminate\Support\Arr;

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

    public static function parse(string $qStr)
    {
        $filters = $qStr != "" ? explode(',', $qStr) : [];

        $filters = collect($filters)->map( fn($f) => self::createWhereClauseParams($f) );

        return $filters;
    }

    public static function createWhereClauseParams(string $str) 
    {
        [$field, $op, $param] = explode('.', $str);

        $op = self::$operators[$op];

        return [$field, $op, $param];
    }
}