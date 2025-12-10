<?php

namespace App\Providers;

use App\Http\Resources\DetailedStockResource;
use App\Http\Resources\StockResource;
use App\Models\ContainerContent;
use App\Models\OrderContent;
use App\Models\Tyre;
use App\Models\Waste;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        $this->app->bind(StockResource::class, function() {
            $order_contents =  OrderContent::select('tyre_id', DB::raw('SUM(qty) AS ordered_qty'))
                ->groupBy('tyre_id');
            $container_contents = ContainerContent::select('tyre_id', DB::raw('SUM(qty) AS supplied_qty'))
                ->groupBy('tyre_id');
            $waste = Waste::select('tyre_id',  DB::raw('SUM(qty) AS wasted_qty'))
                ->groupBy('tyre_id');

            return Tyre::leftJoinSub($container_contents, 'container_contents', function($join) {
                $join->on('container_contents.tyre_id', '=', 'tyres.tyre_id');
            })->leftJoinSub($order_contents, 'order_contents', function($join) {
                $join->on('order_contents.tyre_id', '=', 'tyres.tyre_id');
            })->leftJoinSub($waste, 'waste', function($join) {
                $join->on('waste.tyre_id', '=', 'tyres.tyre_id');
            })->select(
                'tyres.tyre_id',
                'brand', 
                'size', 
                'lisi', 
                'pattern', 
                'created_at',
                'updated_at', 
                DB::raw('IFNULL(ordered_qty,0) AS ordered_qty'),
                DB::raw('IFNULL(supplied_qty,0) AS supplied_qty'),
                DB::raw('IFNULL(wasted_qty,0) AS wasted_qty'),
                DB::raw('IFNULL(supplied_qty,0) - IFNULL(ordered_qty,0) - IFNULL(wasted_qty,0) AS in_stock')
            );
        });

        $this->app->bind(DetailedStockResource::class, function() {
            
            $order_contents =  OrderContent::select('tyre_id', 'container_num', 'bol', DB::raw('SUM(qty) AS ordered_qty'))
                ->groupBy(['tyre_id', 'container_num', 'bol']);
            
            $container_contents = ContainerContent::select('tyre_id', 'Container_num', 'BOL', DB::raw('SUM(qty) AS supplied_qty'), DB::raw('MIN(created_at) AS created_at'))
                ->groupBy('tyre_id', 'Container_num', 'BOL');
            
            $waste = Waste::groupBy(['tyre_id', 'Container_num', 'BOL'])
                ->select(['tyre_id', 'Container_num', 'BOL', DB::raw('SUM(qty) AS wasted_qty')]);
            
            return Tyre::joinSub($container_contents, 'container_contents', function($join) {
                $join->on('container_contents.tyre_id', '=', 'tyres.tyre_id');
            })->leftjoinSub($order_contents, 'order_contents', function($join) {
                $join->on('container_contents.BOL', '=', 'order_contents.bol');
                $join->on('container_contents.Container_num', '=', 'order_contents.container_num');
                $join->on('container_contents.tyre_id', '=', 'order_contents.tyre_id');
            })->leftjoinSub($waste, 'waste', function($join) {
                $join->on('container_contents.BOL', '=', 'waste.BOL');
                $join->on('container_contents.Container_num', '=', 'waste.Container_num');
                $join->on('container_contents.tyre_id', '=', 'waste.tyre_id');
            })->select([
                'container_contents.tyre_id',
                'container_contents.BOL',
                'container_contents.Container_num',
                'container_contents.created_at',
                DB::raw('IFNULL(container_contents.supplied_qty, 0) AS supplied_qty'),
                DB::raw('IFNULL(order_contents.ordered_qty, 0) AS ordered_qty'),
                DB::raw('IFNULL(waste.wasted_qty, 0) AS wasted_qty'),
                DB::raw('IFNULL(container_contents.supplied_qty, 0) - IFNULL(order_contents.ordered_qty, 0) - IFNULL(waste.wasted_qty, 0) AS in_stock')
            ]);
        });

        $this->app->singleton(PersonalAccessToken::class, function() {
            $user = request()->user();
                if ($user) {

                    $token = request()->session()->get('apiToken');

                    if ($token) {
                        return $token;
                    }


                    $token = $user->createToken('default-token')->plainTextToken;
                    
                    request()->session()->put('apiToken', $token);

                    return $token;
                }
                return null;
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
