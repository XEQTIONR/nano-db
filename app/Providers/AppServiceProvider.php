<?php

namespace App\Providers;

use App\Http\Resources\StockResource;
use App\Models\ContainerContent;
use App\Models\OrderContent;
use App\Models\Tyre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(StockResource::class, function() {
            $order_contents =  OrderContent::select('tyre_id', DB::raw('SUM(qty) AS ordered_qty'))
                ->groupBy('order_contents.tyre_id');
            $container_contents = ContainerContent::select('tyre_id', DB::raw('SUM(qty) AS supplied_qty'))
                ->groupBy('container_contents.tyre_id');

            return Tyre::joinSub($container_contents, 'container_contents', function($join) {
                $join->on('container_contents.tyre_id', '=', 'tyres.tyre_id');
            })->joinSub($order_contents, 'order_contents', function($join) {
                $join->on('order_contents.tyre_id', '=', 'tyres.tyre_id');
            })->select(
                'tyres.tyre_id','brand', 'size', 'lisi', 'pattern', 'created_at', 'updated_at', 
                'ordered_qty', 'supplied_qty', DB::raw('supplied_qty - ordered_qty AS in_stock')
            );
        });
    }

    /**
     * Bootstrap any application services.
    */
    public function boot(): void
    {
        //
    }
}
