<?php

use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LetterOfCreditController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TyreController;
use App\Http\Resources\LetterOfCreditResource;
use App\Http\Resources\OrderContentResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\SupplyResource;
use App\Models\ContainerContent;
use App\Models\Order;
use App\Models\OrderContent;
use App\Models\Payment;
use App\Models\Tyre;
use Illuminate\Support\Facades\DB;
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

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

    Route::get('/test', function() {

        $orderBy = 'in_stock';
        $order_contents =  OrderContent::select('tyre_id', DB::raw('SUM(qty) AS ordered_qty'))
            ->groupBy('order_contents.tyre_id');
        $container_contents = ContainerContent::select('tyre_id', DB::raw('SUM(qty) AS supplied_qty'))
            ->groupBy('container_contents.tyre_id');

        $supply = Tyre::joinSub($order_contents, 'order_contents', function($join) {
            $join->on('order_contents.tyre_id', '=', 'tyres.tyre_id');
        })->joinSub($container_contents, 'container_contents', function($join) {
            $join->on('container_contents.tyre_id', '=', 'tyres.tyre_id');
        })->select(
            'tyres.tyre_id','brand', 'size', 'lisi', 'pattern', 'created_at', 'updated_at', 
            'ordered_qty', 'supplied_qty', DB::raw('supplied_qty - ordered_qty AS in_stock')
        )
        ->orderByDesc($orderBy)
        ->paginate(50);
        // return $supply;
        return SupplyResource::collection($supply);
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
