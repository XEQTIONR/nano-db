<?php

use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\LetterOfCreditController;
use App\Http\Controllers\TyreController;
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
    
    Route::get('/tyres', [TyreController::class, 'index'])->name('tyres.index');

    Route::get('/consignments', [ConsignmentController::class, 'index'])->name('consignments.index');
    
    Route::get('/containers', [ContainerController::class, 'index'])->name('containers.index');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
