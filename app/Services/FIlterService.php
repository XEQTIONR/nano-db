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

    public static function parse(string $str, array $map) 
    {
        [$field, $op, $param] = explode('.', $str);

        $op = self::$operators[$op];
        $raw = false;

        if (!empty($map) && Arr::exists($map, $field)) {
            $f = $map[$field]['accessor']; // here
            $raw = $map[$field]['raw']; // here
        }

        return [$f, $op, $param, $raw];
    }
}