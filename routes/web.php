<?php

use App\Http\Resources\LetterOfCreditResource;
use App\Models\LetterOfCredit;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('lcs', function () {
        $data = LetterOfCreditResource::collection(LetterOfCredit::paginate(10));
        return Inertia::render('lcs/index', ['lcs' => $data]);
    })->name('lcs.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
