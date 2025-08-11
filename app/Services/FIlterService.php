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
        'ne' => '<>',
        'like' => 'LIKE'
    ];

    public static function parse(string $qStr)
    {
        $filters = $qStr != "" ? explode(';', $qStr) : [];

        $filters = collect($filters)->map( fn($f) => self::createWhereClauseParams($f) );

        return $filters;
    }

    public static function createWhereClauseParams(string $str) 
    {
        [$field, $op, $param] = explode('.', $str, 3);

        $op = self::$operators[$op];

        if ($op == 'LIKE') {
            $param = "%$param%";
        } 

        return [$field, $op, $param];
    }
}