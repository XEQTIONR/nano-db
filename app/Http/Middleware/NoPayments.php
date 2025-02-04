<?php

namespace App\Http\Middleware;

use Closure;

class NoPayments
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $payments = $request->order->payments;

        if(count($payments)>0)
          return redirect(route('orders.index'));

        return $next($request);
    }
}
