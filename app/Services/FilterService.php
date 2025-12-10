<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
class FilterService
{
    protected static $operators = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '<>',
        'like' => 'like',
        'in' => 'in'
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

        if ($op == 'like') {
            $param = "%$param%";
        }
        
        if ($op == 'in') {
            $param = explode(",", $param);

            if (!is_numeric($param[0])) {
                foreach ($param as $p) {
                    $p = "'$p'";
                }
            }
        }

        return [$field, $op, $param];
    }
}