<?php

namespace App\Listeners;

use App\Events\SalesTransaction;
use Illuminate\Support\Facades\DB;

class SalesOrderChecked
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SalesTransaction  $event
     * @return void
     */
    public function handle(SalesTransaction $event)
    {
        $order = $event->order;
        $products = $order->products()->get();
        foreach ($products as $p){
            if($p->pivot->quantity == 0){
                DB::table('product_sales_order')
                    ->where('sales_order_id', $order->id)
                    ->where('product_id', $p->id)
                    ->update(['status' => '0']);
            }
            else{
                $quantityDo = DB::table('delivery_orders')
                    ->join('delivery_order_product', 'delivery_orders.id', '=', 'delivery_order_product.delivery_order_id')
                    ->where('sales_order_id', $order->id)
                    ->where('product_id', $p->id)
                    ->where('status', '1')
                    ->sum('quantity');
                if($quantityDo == $p->pivot->quantity)
                    DB::table('product_sales_order')
                        ->where('sales_order_id', $order->id)
                        ->where('product_id', $p->id)
                        ->update(['status' => '2']);
                else{
                    $quantityReturn = DB::table('sales_returns')
                        ->join('product_sales_return', 'sales_returns.id', '=', 'product_sales_return.sales_return_id')
                        ->join('delivery_orders', 'delivery_orders.id', '=', 'sales_returns.delivery_order_id')
                        ->where('sales_order_id', $order->id)
                        ->where('product_id', $p->id)
                        ->where('product_sales_return.status', '1')
                        ->sum('quantity');
                    if($quantityReturn === 0 ){
                        if($quantityDo > 0)
                            DB::table('product_sales_order')
                                ->where('sales_order_id', $order->id)
                                ->where('product_id', $p->id)
                                ->update(['status' => '3']);
                        else
                            DB::table('product_sales_order')
                                ->where('sales_order_id', $order->id)
                                ->where('product_id', $p->id)
                                ->update(['status' => '1']);
                    }
                    else if($quantityDo === 0){
                        DB::table('product_sales_order')
                            ->where('sales_order_id', $order->id)
                            ->where('product_id', $p->id)
                            ->update(['status' => '4']);
                    }
                    else{
                        DB::table('product_sales_order')
                            ->where('sales_order_id', $order->id)
                            ->where('product_id', $p->id)
                            ->update(['status' => '5']);
                    }
                }
            }
        }
        $status = '1';
        $payment = DB::table('payment_receipts')
            ->where('sales_order_id', $order->id)
            ->count();
        if($payment !== 0 )
            $status = '3';
        else{
            $products = DB::table('product_sales_order')
                ->where('sales_order_id', $order->id)
                ->get();
            $isComplete = true;
            foreach ($products as $p){
                if(!($p->status == 2 || $p->status == 0 )){
                    $isComplete = false;
                    break;
                }
            }
            if($isComplete)
                $status = '2';
            else
                $status = '1';
        }
        $order->status = $status;
        $order->save();
    }
}
