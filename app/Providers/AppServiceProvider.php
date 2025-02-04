<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
        app()->bind('TyresRemainingInContainers', function(){
          $remaining = DB::select('
      
                  SELECT C.* , IFNULL((qty_bought - qty_sold - IFNULL(waste, 0)), qty_bought) AS in_stock
                  FROM
                    (SELECT Container_num, BOL, tyre_id, SUM(qty) as qty_bought, MIN(created_at) as created_at 
                    FROM container_contents
                    GROUP BY Container_num, BOL, tyre_id) AS C
  
                    LEFT JOIN
  
                    (SELECT container_num, bol, tyre_id, SUM(qty) AS qty_sold
                    FROM order_contents
                    GROUP BY container_num, bol, tyre_id) AS B
	                  
	                  ON (C.tyre_id = B.tyre_id AND C.BOL = B.bol AND C.Container_num = B.container_num)
	                  
	                  LEFT JOIN 
	                  
	                  (SELECT Container_num, BOL, tyre_id, SUM(qty) as waste
	                  FROM waste
	                  GROUP BY Container_num, BOL, tyre_id) AS W
	                  
	                  ON (C.tyre_id = W.tyre_id AND C.BOL = W.BOL AND C.Container_num = W.Container_num)
		              
		              ORDER BY created_at ASC
	    
      
          ');

          return collect($remaining); //because we need collection not array
        });

        app()->bind('TyresRemainingSQL', function(){

          return '
      
          SELECT T.tyre_id, T.brand, T.size, T.pattern, T.lisi, E.qtyavailable AS in_stock
          FROM	(SELECT  C.tyre_id, SUM(C.supplyqty -  IFNULL(B.sumqty,0) - IFNULL(W.waste,0)) AS qtyavailable  
                FROM  (SELECT Container_num, BOL, tyre_id, SUM(qty) as supplyqty 
                      FROM container_contents
                      GROUP BY Container_num, BOL, tyre_id) AS C
    
                      LEFT JOIN
    
                      (SELECT container_num, bol, tyre_id, SUM(qty) AS sumqty
                      FROM order_contents
                      GROUP BY container_num, bol, tyre_id) AS B
                      
                      ON (C.tyre_id = B.tyre_id AND C.BOL = B.bol AND C.Container_num = B.container_num)                      
                      
                      LEFT JOIN 
	                  
                      (SELECT Container_num, BOL, tyre_id, SUM(qty) as waste
                      FROM waste
                      GROUP BY Container_num, BOL, tyre_id) AS W
                      
                      ON (C.tyre_id = W.tyre_id AND C.BOL = W.BOL AND C.Container_num = W.Container_num)
      

                        GROUP BY tyre_id) E, tyres T	
                WHERE T.tyre_id = E.tyre_id
            
            ';

        });

        app()->bind('CustomersOwingSQL', function(){

          $customers = 
            'SELECT C.*, B.*, (B.sum_grand_total - B.sum_payments_total - B.sum_commission) AS balance_total
            FROM customers C 
            LEFT JOIN
            (SELECT customer_id, SUM(sub_total) AS sum_sub_total, 
                    SUM(grand_total) AS sum_grand_total, 
                    SUM(payments_total) AS sum_payments_total,
                    SUM(commission) AS sum_commission,
                    COUNT(*) AS number_of_orders
            FROM
                (SELECT orders.customer_id, ST.sub_total, 
                        (ST.sub_total - ((ST.sub_total*orders.discount_percent)/100) - orders.discount_amount + ((ST.sub_total*orders.tax_percentage)/100) + orders.tax_amount) AS grand_total,
                        IFNULL(payment_total, 0) AS payments_total,
                        orders.commission -- 3. get order info including subtotal, and sum payments
                FROM orders
                  INNER JOIN  
                    (SELECT Order_num, SUM(qty*unit_price) AS sub_total -- 1. get subtotal
                    FROM order_contents
                    GROUP BY Order_num) AS ST        
                  ON orders.Order_num = ST.Order_num
                            
                  LEFT JOIN 
                    (SELECT Order_num, SUM(payment_amount-refund_amount) as payment_total -- 2. get sum of payments
                    FROM payments
                    GROUP BY Order_num) AS P
                  ON orders.Order_num = P.Order_num) AS A

            GROUP BY customer_id) B
            
            ON C.id = B.customer_id
            
            ORDER BY balance_total DESC
          
          ';

          return $customers;
        });

        app()->bind('OrdersSummarySQL', function(){
            return
              'SELECT X.*, (sub_total - discount + tax) AS grand_total, payments_total, (sub_total - discount + tax - commission - payments_total) AS balance
              FROM
                (SELECT T.*, 
                  ((T.sub_total * T.discount_percent/100.0) + T.discount_amount) AS discount,
                  ((T.sub_total * T.tax_percentage/100.0) + T.tax_amount) AS tax,
                  IFNULL(P.payments_total,0) AS payments_total,
                  IFNULL(P.count,0) AS num_payments
                FROM  (SELECT O.*, D.sub_total, C.name 
                      FROM (SELECT Order_num, SUM(unit_price*qty) as sub_total 
                            FROM order_contents 
                            GROUP BY Order_num) D, orders O, customers C 
                      WHERE D.Order_num = O.Order_num AND O.customer_id = C.id) T
            
                LEFT JOIN

                  (SELECT Order_num, (SUM(payment_amount) - SUM(refund_amount)) as payments_total, COUNT(*) AS count 
                  FROM payments 
                  GROUP BY Order_num) P
      
                ON T.Order_num = P.Order_num) X';
        });

        app()->singleton('CurrencyFormatter', function(){
            $fmt = numfmt_create( 'en_IN', \NumberFormatter::DECIMAL );
            $fmt->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 2);
            $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);
            //$fmt->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, "’");
            return $fmt;
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
