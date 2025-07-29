<?php

use App\Http\Controllers\LetterOfCreditController;
use App\Http\Resources\LetterOfCreditResource;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('/lcs', [LetterOfCreditController::class, 'index'])->name('lcs.index');
    Route::get('/lcs/create', [LetterOfCreditController::class, 'create'])->name('lcs.create');
    Route::get('/lcs/{lc}', [LetterOfCreditController::class, 'show'])->name('lcs.show');
    Route::post('/lcs', [LetterOfCreditController::class, 'store'])->name('lcs.store');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
