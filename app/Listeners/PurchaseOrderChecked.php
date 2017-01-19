<?php

namespace App\Listeners;

use App\Events\PurchaseOrderModified;
use Illuminate\Support\Facades\DB;

class PurchaseOrderChecked
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
     * @param  PurchaseOrderModified  $event
     * @return void
     */
    public function handle(PurchaseOrderModified $event)
    {
        $order = $event->order;
        $products = $order->products()->get();
        $poStatus = '0';
        foreach ($products as $p){
            if($p->pivot->quantity == 0){
                DB::table('product_purchase_order')
                    ->where('purchase_order_id', $order->id)
                    ->where('product_id', $p->id)
                    ->update(['status' => '0']);
            }
            else{
                $quantityReceipt = DB::table('product_receipts')
                    ->join('product_product_receipt', 'product_receipts.id', '=', 'product_product_receipt.product_receipt_id')
                    ->where('purchase_order_id', $order->id)
                    ->where('product_id', $p->id)
                    ->sum('quantity');
                if($quantityReceipt == $p->pivot->quantity){
                    DB::table('product_purchase_order')
                        ->where('purchase_order_id', $order->id)
                        ->where('product_id', $p->id)
                        ->update(['status' => '2']);
                }
                else{
                    $quantityReturn = DB::table('purchase_returns')
                        ->join('product_purchase_return', 'purchase_returns.id', '=', 'product_purchase_return.purchase_return_id')
                        ->where('purchase_order_id', $order->id)
                        ->where('product_id', $p->id)
                        ->where('status', '1')
                        ->sum('quantity');
                    if($quantityReturn === 0 ){
                        if($quantityReceipt > 0)
                            DB::table('product_purchase_order')
                                ->where('purchase_order_id', $order->id)
                                ->where('product_id', $p->id)
                                ->update(['status' => '3']);
                        else
                            DB::table('product_purchase_order')
                                ->where('purchase_order_id', $order->id)
                                ->where('product_id', $p->id)
                                ->update(['status' => '1']);
                    }
                    else if($quantityReceipt === 0){
                        DB::table('product_purchase_order')
                            ->where('purchase_order_id', $order->id)
                            ->where('product_id', $p->id)
                            ->update(['status' => '4']);
                    }
                    else{
                        DB::table('product_purchase_order')
                            ->where('purchase_order_id', $order->id)
                            ->where('product_id', $p->id)
                            ->update(['status' => '5']);
                    }
                }
            }

        }
        $payment = DB::table('purchase_payments')
            ->where('purchase_order_id', $order->id)
            ->count();
        if($payment !== 0 )
            $poStatus = '3';
        else{
            $products = DB::table('product_purchase_order')
                ->where('purchase_order_id', $order->id)
                ->get();
            $isComplete = true;
            foreach ($products as $p){
                if(!($p->status == 2 || $p->status == 0 )){
                    $isComplete = false;
                    break;
                }
            }
            if($isComplete)
                $poStatus = '2';
            else
                $poStatus = '1';
        }
        $order->status = $poStatus;
        $order->save();
    }
}
