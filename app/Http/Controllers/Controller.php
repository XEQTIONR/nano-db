<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
    public static function middleware(): array
    {
        return ['auth'];
    }
}
